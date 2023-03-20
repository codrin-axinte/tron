<?php

namespace App\Telegram\Data;

use App\Telegram\DefaultWebhookHandler;
use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class SetHandlerData extends Data
{
    public function __construct(
        public TelegraphBot          $bot,
        public TelegraphChat         $chat,
        public int                   $messageId,
        public int                   $callbackQueryId,
        public DefaultWebhookHandler $handler,
        public Request               $request,
        public Collection            $data,
        public Keyboard              $originalKeyboard,
        public Message|null          $message = null,
        public CallbackQuery|null    $callbackQuery = null,
        public ?\App\Models\User     $currentUser = null
    )
    {
    }
}
