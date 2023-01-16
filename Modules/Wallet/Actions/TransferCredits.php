<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Models\Wallet;

class TransferCredits
{
    public function __invoke(Wallet $walletFrom, Wallet $walletTo, int|float $amount): void
    {
        // Is the user authorized? Check for permissions
        // Check to see if the user doesn't transfer his own wallet

        if ($walletFrom->amount <= $amount) {
            // Throw exception
            return;
        }

        $walletFrom->decrement($amount);
        $walletTo->increment($amount);
    }
}
