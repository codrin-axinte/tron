<?php

namespace App\Console\Commands;

use App\Actions\Tron\SyncWallet;
use App\Services\PoolManager;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Modules\Wallet\Models\Wallet;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;
use Throwable;

class TronSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tron:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs all wallets, accounts and pools with the blockchain data.';

    /**
     * Execute the console command.
     *
     * @param SyncWallet $syncWallet
     * @param PoolManager $poolManager
     * @throws GuzzleException
     * @throws SaloonException
     * @throws ReflectionException
     * @throws Throwable
     * @return int
     */
    public function handle(SyncWallet $syncWallet, PoolManager $poolManager): int
    {
        $wallets = Wallet::query()
            ->with('user')
            ->whereNotNull('address')
            ->lazy();

        foreach ($wallets as $wallet) {
            $syncWallet->sync($wallet);
        }

       // $service->syncAccounts();
//        $poolManager->sync();

        return self::SUCCESS;
    }
}
