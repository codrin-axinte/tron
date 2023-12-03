<?php

namespace App\Jobs;

use App\Actions\Tron\SyncWallet;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class SyncWalletJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private readonly Wallet $wallet)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param SyncWallet $syncWallet
     * @return void
     * @throws GuzzleException
     * @throws \ReflectionException
     * @throws SaloonException
     */
    public function handle(SyncWallet $syncWallet): void
    {
        $syncWallet->sync($this->wallet);
    }
}
