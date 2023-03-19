<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Models\Wallet;

class DepositCredits
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Wallet $wallet, int|float $amount, string $source = null)
    {
        //TODO: Is the user authorized? Check for permissions

        return \DB::transaction(function () use ($wallet, $amount, $source) {
            $wallet->increment('amount', $amount);

            $wallet->transactions()->create([
                'user_id' => $wallet->user_id,
                'amount' => $amount,
                'type' => WalletTransactionType::In,
                'description' => $source,
            ]);
        });
    }
}
