<?php

namespace App\Telegram\Renderers;

use App\ValueObjects\USDT;
use Modules\Wallet\Models\Wallet;

class WalletRenderer
{
    public function render(Wallet $wallet): string
    {
        $message = "Your wallet information is: \n\n";
        $balance = USDT::make($wallet->amount)->formatted();
        $message .= "\n💵Balance: *" . $balance . ' USD*';
        $message .= "\n🏦Address: *" . $wallet->address . '*';

        return $message;
    }
}
