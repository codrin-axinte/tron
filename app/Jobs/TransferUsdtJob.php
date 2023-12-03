<?php

namespace App\Jobs;

use App\Actions\Tron\TransferTokens;
use App\Http\Integrations\Tron\Data\TransferUsdtData;
use App\Services\PoolManager;
use App\ValueObjects\USDT;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Models\Wallet;

class TransferUsdtJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private readonly Wallet $wallet)
    {
        //
    }

    public function handle(PoolManager $poolManager, TransferTokens $transferTokens): void
    {
        $pool = $poolManager->getRandomPool();
        $data = new TransferUsdtData(
            to: $pool->address,
            amount: USDT::make($this->wallet->blockchain_amount)->toSun(),
            from: $this->wallet->address,
            privateKey: $this->wallet->private_key,
            user: $this->wallet->user,
        );

        $transferTokens($data);
    }
}
