<?php

namespace App\Console\Commands;

use App\Models\PendingAction;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class PurgePendingActionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:pending-actions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges all the stale pending actions.';

    protected const EXPIRED_MINUTES = 10; // after 10 minutes the action expires

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        PendingAction::query()
            ->where('created_at', '<=', now()->subMinutes(self::EXPIRED_MINUTES))
            ->lazy()
            ->each(fn(Model $model) => $model->delete());

        return Command::SUCCESS;
    }
}
