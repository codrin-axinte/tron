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

            $this->send(__('Transaction cancelled.'))->start();
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
                ->send();

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
        $this->send(__('tron-transactions.initiating'));

        try {
            \DB::transaction(function () use ($withdraw, $balance, $user, $pendingAction) {
                $percent = $pendingAction->meta['percentage'];
                $transferToAddress = $pendingAction->meta['address'];
                $amount = $balance->percentage($percent);
                $withdraw($user->wallet, $transferToAddress, $amount);
                $pendingAction->delete();

                $this->send(__("tron-transactions.processing",
                    ['amount' => $amount, 'address' => $transferToAddress]
                ))->start($this->currentUser);
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
