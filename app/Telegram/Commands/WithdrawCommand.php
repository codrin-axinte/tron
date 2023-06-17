<?php

namespace App\Telegram\Commands;

use App\Actions\Tron\Withdraw;
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
        // TODO: Check if the account is older than
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
            $this->error(__('You are not authorized to perform this action.'))->dispatch();

            return;
        }

        $balance = USDT::make($user->wallet->amount);

        if ($resetAction) {
            $pendingAction = PendingAction::find($resetAction);
            $pendingAction->delete();
            // TODO: Investigate what is this?
            $this->markdown(__('You are not authorized to perform this action.'))->dispatch();
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
            $this->markdown(__('Please, input the wallet address where should transfer the money.'))->dispatch();
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
            $this->error()->dispatch();
            return;
        }

        $withdraw = app(Withdraw::class);
        $this->markdown(__('Initiating transaction...'))->dispatch();

        try {
            \DB::transaction(function () use ($withdraw, $balance, $user, $pendingAction) {
                $percent = $pendingAction->meta['percentage'];
                $transferToAddress = $pendingAction->meta['address'];
                $amount = $balance->percentage($percent);
                $transaction = $withdraw($user->wallet, $transferToAddress, $amount);
                $pendingAction->delete();
                $this->markdown('Transaction processed. You have transferred *' . $amount . '* USDT to the address ' . $transferToAddress)->dispatch();
                //$this->markdown($transaction->blockchain_reference_id)->dispatch();
            });

        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), ['stracktrace' => $e->getTraceAsString()]);
            $this->error($e->getMessage())->dispatch();
        }

    }

    public function authorized(): bool
    {
        return $this->isAuth();
    }
}
