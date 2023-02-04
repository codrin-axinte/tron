<?php

namespace App\Telegram\Traits;

use App\Enums\ChatHooks;
use App\Models\MessageTemplate;

trait HasMessageTemplates
{
    protected function sendTemplate(mixed $hooks): static
    {
        $templates = $this->findTemplates($hooks);

        foreach ($templates as $template) {
            $this->chat->markdown($template->content)->send();
        }

        return $this;
    }


    private function findTemplates(mixed $hooks)
    {
        if (is_array($hooks)) {
            $hooks = array_map(fn($hook) => $hook instanceof ChatHooks ? $hook->value : $hook, $hooks);
        } else if ($hooks instanceof ChatHooks) {
            $hooks = $hooks->value;
        }

        return MessageTemplate::query()->whereInHooks(is_array($hooks) ? $hooks : [$hooks])->get();
    }
}
