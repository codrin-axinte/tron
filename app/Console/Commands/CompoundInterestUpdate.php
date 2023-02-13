<?php

namespace App\Console\Commands;

use App\Actions\MLM\UpdateUsersWalletsByCurrentBalance;

use Illuminate\Console\Command;

class CompoundInterestUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlm:compound-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the wallets based on the subscribed plan.';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        app(config('compound_interest_update_action'))->execute();

        return Command::SUCCESS;
    }
}
