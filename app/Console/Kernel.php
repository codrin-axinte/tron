<?php

namespace App\Console;

use App\Console\Commands\CompoundInterestUpdate;
use App\Console\Commands\PurgePendingActionsCommand;
use App\Console\Commands\TronSyncCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Telescope\Console\PruneCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(PruneCommand::class)->daily();
        $schedule->command(CompoundInterestUpdate::class)->everyTenMinutes()->withoutOverlapping();
       // $schedule->command(TronSyncCommand::class)->hourly()->withoutOverlapping();
        $schedule->command(PurgePendingActionsCommand::class)->everyTenMinutes();
        // Referral
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
