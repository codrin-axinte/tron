<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Exceptions\InsufficientCredits;
use Modules\Wallet\Models\Wallet;

class PurchaseWithCredits
{
    /**
     * @throws InsufficientCredits
     * @throws \Throwable
     */
    public function __invoke(Wallet $wallet, int|float $amount, ?string $description = null, callable $callback = null)
    {
        if ($amount > $wallet->amount) {
            throw new InsufficientCredits('Nu ai suficiente credite in cont.', 403);
        }

        return \DB::transaction(function () use ($callback, $wallet, $amount, $description) {
            $wallet->decrement('amount', $amount);

            $wallet->transactions()->create([
                'user_id' => $wallet->user_id,
                'amount' => $amount,
                'type' => WalletTransactionType::Out,
                'description' => $description,
            ]);

            return value($callback);
        });
    }
}
