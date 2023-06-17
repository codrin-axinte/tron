<?php

namespace App\Telegram\Commands;

use App\Actions\Tron\Withdraw;
use App\Enums\MessageType;
use App\Enums\PendingActionType;
use App\Models\PendingAction;
use App\Models\User;
use App\Telegram\Traits\HasChatMenus;
use App\ValueObjects\Percentage;
use App\ValueObjects\USDT;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use GuzzleHttp\Exception\GuzzleException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class WithdrawCommand extends TelegramCommand
{
    use HasChatMenus;

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function __invoke(): void
    {
        $percent = $this->data->get('percent', false);
        // $transferToAddress = $this->data->get('account', false);
        $resetAction = $this->data->get('reset', false);
        $userId = $this->data->get('user');
        if ($userId) {
            $user = User::with(['wallet'])->find($userId);
        } else {
            $user = $this->currentUser;
        }

        if (!$user) {
            $this->send(__('messages.unauthorized'), MessageType::Error)
                ->start();

            return;
        }

        $balance = USDT::make($user->wallet->amount);

        if ($resetAction) {
            $pendingAction = PendingAction::find($resetAction);
            $pendingAction->delete();
            return;
        }

        if (!$percent && !$userId) {
            $keyboard = $this->choosePercentageMenu($balance, 'withdraw');
            $message = __(
                'How much do you want to withdraw? Your current balance is: :balance',
                ['balance' => $balance->formatted()]
            );

            $this->markdown($message)
                ->keyboard($keyboard)
                ->dispatch();

            return;
        }

        if (!$userId) {
            $this->send(__('Please, send the wallet address where should transfer the money.'));
            $user->pendingActions()->create([
                'meta' => [
                    'percentage' => (int)$percent
                ],
                'type' => PendingActionType::Withdraw,
            ]);

            return;
        }

        $pendingAction = $user->pendingActions()
            ->where('type', PendingActionType::Withdraw)
            ->latest()
            ->first();

        //\Log::debug(__('Pending Action'), ['action' => $pendingAction->id]);

        if (!$pendingAction) {
            $this->send(__('messages.error'), MessageType::Error)->start();
            return;
        }

        $withdraw = app(Withdraw::class);
        $this->send(__('Initiating transaction...'));

        try {
            \DB::transaction(function () use ($withdraw, $balance, $user, $pendingAction) {
                $percent = $pendingAction->meta['percentage'];
                $transferToAddress = $pendingAction->meta['address'];
                $amount = $balance->percentage($percent);
                $transaction = $withdraw($user->wallet, $transferToAddress, $amount);
                $pendingAction->delete();
                $this->send(__(
                    "Transaction processed. You have transferred * :amount * USDT to the address :address",
                    ['amount' => $amount, 'address' => $transferToAddress]
                ));
                //$this->markdown($transaction->blockchain_reference_id)->dispatch();
            });

        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), ['stracktrace' => $e->getTraceAsString()]);
            $this->send($e->getMessage(), MessageType::Error);
        }

    }

    public function authorized(): bool
    {
        return $this->isAuth();
    }
}
