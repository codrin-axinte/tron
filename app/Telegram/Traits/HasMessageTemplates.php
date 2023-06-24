<?php

namespace App\Telegram\Traits;

use App\Enums\ChatHooks;
use App\Models\MessageTemplate;
use DefStudio\Telegraph\Models\TelegraphChat;

trait HasMessageTemplates
{
    protected function sendTemplate(mixed $hooks, ?TelegraphChat $chat = null): static
    {
        $templates = $this->findTemplates($hooks);

        $chat = $chat ?? $this->chat;

        foreach ($templates as $template) {
            $chat->markdown($template->content)->send();
        }

        return $this;
    }

    protected function findTemplates(mixed $hooks)
    {
        if (is_array($hooks)) {
            $hooks = array_map(fn($hook) => $hook instanceof ChatHooks ? $hook->value : $hook, $hooks);
        } elseif ($hooks instanceof ChatHooks) {
            $hooks = $hooks->value;
        }

        return MessageTemplate::query()->whereInHooks(is_array($hooks) ? $hooks : [$hooks])->get();
    }
}
