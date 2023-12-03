<?php

namespace App\Console\Commands;

use App\Jobs\SyncPoolJob;
use App\Jobs\SyncWalletJob;
use App\Models\Pool;
use App\Models\TronTransaction;
use Illuminate\Console\Command;
use Modules\Wallet\Models\Wallet;
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
     * @return int
     * @throws Throwable
     */
    public function handle(): int
    {
        $this->syncWallets();
        $this->syncPools();;
        $this->syncTransactions();

        // $service->syncAccounts();
        return self::SUCCESS;
    }

    private function syncTransactions(): void
    {
        $transactions = TronTransaction::query()
            ->approved()
            ->pending()
            ->cursor();

        foreach ($transactions as $transaction) {
            $request = '';
        }
    }

    private function syncWallets(): void
    {
        $wallets = Wallet::query()
            ->with('user')
            ->whereNotNull('address')
            ->cursor();

        $this->info('Dispatching wallet jobs...');
        $this->output->progressStart($wallets->count());
        foreach ($wallets as $wallet) {
            // Dispatch jobs for each pool
            dispatch(new SyncWalletJob($wallet));
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }

    private function syncPools(): void
    {
        $pools = Pool::query()->select(['id', 'address'])->whereNotNull('address')->cursor();

        $this->info('Dispatching pool jobs...');
        $this->output->progressStart($pools->count());
        foreach ($pools as $pool) {
            // Dispatch jobs for each pool
            dispatch(new SyncPoolJob($pool));
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }
}
