<?php

namespace App\Telegram\Commands;

use App\Telegram\Renderers\WalletRenderer;

class ShowWalletCommand extends TelegramCommand
{
    public function __construct(private WalletRenderer $walletRenderer)
    {
    }

    public function __invoke(): void
    {
        $message = $this->walletRenderer->render($this->currentUser->wallet);

        $this
            ->send($message)
            ->start($this->currentUser);
    }
}
