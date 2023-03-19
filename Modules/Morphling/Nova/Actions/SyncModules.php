<?php

namespace Modules\Morphling\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Modules\Morphling\Services\Morphling;

class SyncModules extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        try {
            $this->morphling()->syncModules();
        } catch (\Exception $exception) {
            return Action::danger($exception->getMessage());
        }

        return Action::message(__('Modules synced!'));
    }

    private function morphling(): Morphling
    {
        return app(Morphling::class);
    }
}
