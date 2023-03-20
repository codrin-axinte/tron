<?php

namespace App\Telegram\Commands;

use App\Telegram\Data\SetHandlerData;
use App\Telegram\DefaultWebhookHandler;
use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin DefaultWebhookHandler
 */
abstract class TelegramCommand
{
    use ForwardsCalls;

    protected TelegraphBot $bot;
    protected TelegraphChat $chat;
    protected ?int $messageId = null;
    protected ?int $callbackQueryId = null;
    protected DefaultWebhookHandler $handler;
    protected Request $request;
    protected Message|null $message = null;
    protected CallbackQuery|null $callbackQuery = null;
    protected Collection $data;
    protected Keyboard $originalKeyboard;

    protected ?\App\Models\User $currentUser = null;

    public function __call(string $name, array $arguments)
    {
        return $this->forwardCallTo($this->handler, $name, $arguments);
    }

    public function setHandler(SetHandlerData $payload): static
    {
        $this->bot = $payload->bot;
        $this->chat = $payload->chat;
        $this->messageId = $payload->messageId;
        $this->callbackQuery = $payload->callbackQuery;
        $this->callbackQueryId = $payload->callbackQueryId;
        $this->handler = $payload->handler;
        $this->message = $payload->message;
        $this->data = $payload->data;
        $this->originalKeyboard = $payload->originalKeyboard;
        $this->currentUser = $payload->currentUser;

        return $this;
    }

    protected function runCommand(string $command, array $arguments = [])
    {
        return $this->forwardCallTo($this->handler, $command, $arguments);
    }

    public function authorized(): bool
    {
        return true;
    }

    protected function isAuth(): bool
    {
        return (bool)$this->currentUser;
    }

    protected function isGuest(): bool
    {
        return !$this->currentUser;
    }

    protected function message(string $message): \DefStudio\Telegraph\Telegraph
    {
        return $this->chat->message($message);
    }

    protected function markdown(string $message): \DefStudio\Telegraph\Telegraph
    {
        return $this->chat->markdown($message);
    }

    protected function success($message): \DefStudio\Telegraph\Telegraph
    {
        return $this->chat->markdown('🎉' . $message);
    }

    protected function error($message = 'I am sorry, I could not do that. Something went wrong.'): \DefStudio\Telegraph\Telegraph
    {
        return $this->chat->markdown('💀' . $message);
    }

    protected function ask($question): \DefStudio\Telegraph\Telegraph
    {
        return $this->chat->markdown('❓' . $question);
    }

    protected function sendTyping(): static
    {
        $this->chat->action(ChatActions::TYPING)->send();

        return $this;
    }
}
