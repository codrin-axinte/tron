<?php

namespace Modules\Wallet\Listeners;

use Modules\Wallet\Nova\WalletTool;

class RegisterWalletTools
{
    public function __invoke(): array
    {
        return [
            WalletTool::make()->canSee(fn () => true),
        ];
    }
}
