<?php

namespace App\Console\Commands;

use App\Updater\Updater;
use Illuminate\Console\Command;

class AppUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the application to the latest version.';

    /**
     * Execute the console command.
     *
     *
     * @throws \Throwable
     */
    public function handle(): int
    {
        return app(Updater::class)
            ->withCommand($this)
            ->update() ? Command::SUCCESS : Command::FAILURE;
    }
}
