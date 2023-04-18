<?php

namespace App\Telegram\Traits;

use App\Models\User;
use App\Telegram\DefaultWebhookHandler;

/**
 * @mixin DefaultWebhookHandler
 */
trait ExtendsSetupChat
{
    protected ?User $currentUser = null;

    protected function setupChat(): void
    {
        parent::setupChat();

        $this->currentUser = $this->getCurrentUser();
    }

    private function getCurrentUser(): ?User
    {
        return User::query()->with(['wallet'])->firstWhere('chat_id', $this->chat->id);
    }

    protected function currentUser(): ?User
    {
        if (! $this->currentUser) {
            $this->currentUser = $this->getCurrentUser();
        }

        return $this->currentUser;
    }
}
