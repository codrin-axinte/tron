<?php

namespace App\Telegram;

use App\Enums\PendingActionType;
use App\Models\PendingAction;
use App\Models\User;
use App\Telegram\Data\SetHandlerData;
use App\Telegram\Traits\ExtendsSetupChat;
use DefStudio\Telegraph\DTO\InlineQuery;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Stringable;
use ReflectionMethod;

/**
 * @method start(?User $user = null)
 */
class DefaultWebhookHandler extends WebhookHandler
{
    use ExtendsSetupChat;

    protected int $messageId = 0;

    protected int $callbackQueryId = 0;

    /**
     * @var array|string[]
     */
    protected array $commands = [];

    public function __construct(array $commands = [])
    {
        parent::__construct();
        $this->commands = $commands;
    }

    /**
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (!array_key_exists($name, $this->commands)) {
            $this->handleUnknownCommand(new Stringable($name));

            return;
        }

        $command = app($this->commands[$name]);
        $command->setHandler(
            new SetHandlerData(
                bot: $this->bot,
                chat: $this->chat,
                messageId: $this->messageId,
                callbackQueryId: $this->callbackQueryId,
                handler: $this,
                request: $this->request,
                data: $this->data,
                originalKeyboard: $this->originalKeyboard,
                message: $this->message,
                callbackQuery: $this->callbackQuery,
                currentUser: $this->currentUser
            )
        );

        if ($command->authorized()) {
            $command(...$arguments);
        } else {
            $this->chat->message("👮 " . __('You are not authorized to use this command.'))->send();
        }

    }

    protected function handleChatMessage(Stringable $text): void
    {
        $user = $this->currentUser;

        if (!$user) {
            \Log::error('User not found', ['user' => $user, 'message' => $text->value()]);
            return;
        }

        /**
         * @var PendingAction $pendingAction
         */
        $pendingAction = $user
            ->pendingActions()
            ->latest()
            ->first();

        if (!$pendingAction) {
            return;
        }

        switch ($pendingAction->type) {
            case PendingActionType::Withdraw:
                $this->handleWithdrawPendingAction($pendingAction, $user, $text->value());
                break;
            case PendingActionType::Trade:
                $this->handleTradePendingAction($pendingAction, $user, $text->value());
                break;
        }
    }

    private function handleTradePendingAction(PendingAction $pendingAction, User $user, string $value): void
    {
        // silence is golden
        $wallet = $user->wallet;
        $amount = filter_var($value, FILTER_VALIDATE_FLOAT);

        if ($amount > $wallet->amount) {
            $this->chat
                ->markdown(
                    __('You cannot trade more than you have. Your balance is :balance. Please, specify another value.',
                        ['balance' => $wallet->amount]
                    )
                )->send();
            return;
        }

        $pendingAction->mergeMeta([
            'amount' => $amount,
        ])->save();

        $this->chat
            ->markdown(__('Is this the correct amount? :amount', ['amount' => $amount]))
            ->keyboard(Keyboard::make()->buttons([
                Button::make('✅ ' . __('Yes, that is correct'))
                    ->action('trade')
                    ->param('user', $user->id),
                Button::make('❌ ' . __('No, cancel transaction'))
                    ->action('trade')
                    ->param('reset', $pendingAction->id),
            ]))->send();

    }

    private function handleWithdrawPendingAction(PendingAction $pendingAction, User $user, string $value): void
    {
        if ($value === $user->wallet->address) {
            $this->chat
                ->markdown(__('You cannot use the same wallet address. Please, use a different one.'))
                ->send();
            return;
        }

        $pendingAction->mergeMeta([
            'address' => $value,
        ])->save();

        $this->chat
            ->markdown(__('Is this the correct address? :address', ['address' => $value]))
            ->keyboard(Keyboard::make()->buttons([
                Button::make('✅ ' . __('Yes, that is correct'))
                    ->action('withdraw')
                    ->param('user', $user->id),
                Button::make('❌ ' . __('No, cancel transaction'))
                    ->action('withdraw')
                    ->param('reset', $pendingAction->id),
            ]))->send();
    }


    protected function canHandle(string $action): bool
    {
        if ($action === 'handle') {
            return false;
        }

        if (!method_exists($this, $action)) {
            if (array_key_exists($action, $this->commands)) {
                return true;
            }

            return false;
        }

        $reflector = new ReflectionMethod($this::class, $action);
        if (!$reflector->isPublic()) {
            return false;
        }

        return true;
    }

    protected function handleInlineQuery(InlineQuery $inlineQuery): void
    {
        parent::handleInlineQuery($inlineQuery); // TODO: Change the autogenerated stub
    }
}
