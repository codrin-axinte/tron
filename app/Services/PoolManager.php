<?php

namespace App\Services;

use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Models\Pool;
use App\Models\User;
use Modules\Wallet\Models\Wallet;

class PoolManager
{
    public function __construct(private TronService $tronService)
    {
    }

    public function transfer(Wallet $wallet, Pool $pool = null)
    {
        // A pool should manage a number of accounts

    }

    public function aggregateWallets(Pool $pool = null)
    {
        // Should aggregate all wallets into random pools
    }

    public function withdraw(Wallet $wallet, float $amount, Pool $pool = null)
    {
        // Should transfer for a pool to a requested wallet
    }

    public function sync(): static
    {
        $pools = Pool::query()->select(['id', 'address'])->whereNotNull('address')->lazy();

        $request = GetAccountBalanceRequest::make();

        foreach ($pools as $pool) {
            $request->addData('address', $pool->address);
            $response = $request->send();

            $pool->update(['balance' => $response->json()]);
        }

        return $this;
    }

    public function createRandom(int $amount = 1, bool $isCentral = false)
    {
        if ($amount === 1) {
            $wallet = $this->tronService->generateWallet();

            return Pool::create([
                'private_key' => $wallet->privateKey,
                'public_key' => $wallet->publicKey,
                'address' => $wallet->address,
                'mnemonic' => $wallet->mnemonic,
                'is_central' => $isCentral,
            ]);
        }

        for ($i = 0; $i < $amount; $i++) {
            $this->createRandom();
        }

        return $this;
    }
}
