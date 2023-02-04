<?php

namespace App\Telegram\Traits;

trait ExtendsSetupChat
{
    protected ?\App\Models\User $currentUser = null;

    protected function setupChat(): void
    {
        parent::setupChat();

        $this->currentUser = $this->getCurrentUser();
    }

    private function getCurrentUser(): ?\App\Models\User
    {
        if (!$this->currentUser) {
            $this->currentUser = $this->chat
                ->belongsToMany(\App\Models\User::class, 'chat_user')
                ->with([
                    'wallet',
                ])->first();
        }

        return $this->currentUser;
    }
    protected function isAuth(): bool
    {
        return (bool)$this->currentUser;
    }

    protected function isGuest(): bool
    {
        return !$this->currentUser;
    }
}
