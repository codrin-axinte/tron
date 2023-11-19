<?php

namespace App\Services;

use App\Actions\Tron\TransferTokens;
use App\Http\Integrations\Tron\Data\Responses\GenerateWalletResponseData;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Data\WithdrawSettings;
use App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest;
use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Models\Pool;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class PoolManager
{
    public function __construct(private TronService $tronService)
    {
    }

    /**
     * Aggregate all the wallets into pools
     *
     * @return void
     *
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
        $transferTokens = app(TransferTokens::class);
        Wallet::query()
            ->where('blockchain_amount', '>', $minAmountToTransfer)
            ->chunk($chunkByPools, function ($wallets) use ($except, $transferTokens) {

                $pool = Pool::query()->inRandomOrder()->whereNotCentral()->whereNotIn('id', $except->toArray())->first();

                foreach ($wallets as $wallet) {

                    $transferTokens->run(new TransferTokensData(
                        to: $pool->address,
                        amount: $wallet->blockchain_amount,
                        from: $wallet->address,
                        privateKey: $wallet->private_key
                    ));
                }

                $except->push($pool->id);
            });

    }

    public function aggregateAmountFor(Pool $currentPool, float $tokensAmount)
    {
        $pools = Pool::query()
            ->whereNot('id', $currentPool->id)
            ->where('balance', '>', 0)
            ->whereNotCentral()
            ->get();

    }

    public function getRandomPool(?float $tokensAmount = null): ?Pool
    {
        $proxies = (int)nova_get_setting('max_pool_proxy', 0);

        $query = Pool::query()
            ->when(!is_null($tokensAmount), fn($query) => $query->where('balance', '>=', $tokensAmount))
            ->inRandomOrder();

        if ($proxies > 0) {
            $query->whereNotCentral();
        }

        return $query->first();
    }


    public function sync(): static
    {
        $pools = Pool::query()->select(['id', 'address'])->whereNotNull('address')->lazy();
        // TODO: Improve performance with promises - https://medium.com/@ardanirohman/how-to-handle-async-request-concurrency-with-promise-in-guzzle-6-cac10d76220e
        // Dispatch jobs for each pool
        $request = GetAccountBalanceRequest::make();

        foreach ($pools as $pool) {
            $request->addData('address', $pool->address);
            try {
                $response = $request->send();
                $pool->update(['balance' => $response->json() ?? 0]);
            } catch (\Throwable $e) {
                throw $e;
            }


        }

        return $this;
    }

    public function createRandom(int $amount = 1, bool $isCentral = false)
    {
        if ($amount === 1) {

            $wallet = GenerateWalletResponseData::from(GenerateRandomWalletRequest::make()->send()->json());

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
