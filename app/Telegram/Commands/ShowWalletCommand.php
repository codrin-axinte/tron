<?php

namespace App\Telegram\Commands;

use App\Telegram\Renderers\WalletRenderer;

class ShowWalletCommand extends TelegramCommand
{

    public function __construct(private WalletRenderer $walletRenderer)
    {
    }

    public function __invoke()
    {
        $this->chat
            ->markdown(
                $this->walletRenderer->render($this->currentUser->wallet, true)
            )
            ->send();
    }
}
