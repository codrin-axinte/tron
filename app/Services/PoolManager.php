<?php

namespace App\Services;

use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Http\Integrations\Tron\Requests\TRC20\TransferTokensRequest;
use App\Models\Pool;
use App\Models\User;
use Modules\Wallet\Models\Wallet;

class PoolManager
{
    public function __construct(private TronService $tronService)
    {
    }


    /**
     * Aggregate all the wallets into pools
     *
     * @param $minAmountToTransfer
     * @param $chunks
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     * @throws \Sammyjo20\Saloon\Exceptions\SaloonException
     */
    public function aggregateWallets($minAmountToTransfer = 50, $chunks = 15)
    {
        $except = collect();
        $totalPools = Pool::query()->whereNotCentral()->count();
        $chunkByPools = round($chunks / $totalPools);

        // TODO: Improve performance using guzzle send async

        // Aggregate all wallets into a random pool
        Wallet::query()
            ->where('blockchain_amount', '>', $minAmountToTransfer)
            ->chunk($chunkByPools, function ($wallets) use ($except, $minAmountToTransfer) {

                $pool = Pool::query()->inRandomOrder()->whereNotCentral()->whereNotIn('id', $except->toArray())->first();

                foreach ($wallets as $wallet) {

                    $this->tronService->transfer(new TransferTokensData(
                        to: $pool->address,
                        amount: $wallet->blockchain_amount,
                        from: $wallet->address,
                        privateKey: $wallet->private_key
                    ), $wallet, $pool);
                }

                $except->push($pool->id);
            });

    }

    public function getRandomPool(): Pool
    {
        return Pool::query()->inRandomOrder()->whereNotCentral()->first();
    }

    public function sync(): static
    {
        $pools = Pool::query()->select(['id', 'address'])->whereNotNull('address')->lazy();
        // TODO: Improve performance with promises - https://medium.com/@ardanirohman/how-to-handle-async-request-concurrency-with-promise-in-guzzle-6-cac10d76220e
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
