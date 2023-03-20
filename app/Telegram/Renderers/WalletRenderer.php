<?php

namespace App\Telegram\Renderers;

use Modules\Wallet\Models\Wallet;

class WalletRenderer
{
    public function render(Wallet $wallet, bool $fullDetails = false): string
    {
        $message = "Your wallet information is: \n\n";

        $message .= "\n💵Balance: *" . $wallet->amount . ' USD*';
        $message .= "\n🏦Address: *" . $wallet->address . '*';

        return $message;
    }
}
