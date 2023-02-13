<?php

namespace App\Console\Commands;

use App\Services\PoolManager;
use App\Services\TronService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class SyncAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tron:sync-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all wallets with the blockchain data.';

    /**
     * Execute the console command.
     *
     * @param TronService $service
     * @return int
     * @throws GuzzleException
     * @throws \ReflectionException
     * @throws SaloonException
     */
    public function handle(TronService $service, PoolManager $poolManager): int
    {

        $service->syncWallets();
        $service->syncAccounts();
        $poolManager->sync();


        return Command::SUCCESS;
    }
}
