<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Models\Wallet;

class CreateTransaction
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Wallet|int|string $wallet, int $amount, string $description = null, WalletTransactionType $type = WalletTransactionType::Out)
    {
        if (! ($wallet instanceof Wallet)) {
            $wallet = Wallet::findOrFail($wallet);
        }

        return \DB::transaction(fn () => $wallet->transactions()->create([
            'user_id' => $wallet->user_id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
        ]));
    }
}
