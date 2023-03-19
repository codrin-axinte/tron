<?php

namespace Modules\Morphling\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Modules\Morphling\Utils\BulkActionFluent;

abstract class BulkAction extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $action = $this->getBulkActionOptions(app(BulkActionFluent::class));

        $action->run($models, fn ($model) => $this->runAction($model, $fields));

        return Action::message(__('Processing :resource...', ['resource' => $action->getResourceName()]));
    }

    protected function getBulkActionOptions(BulkActionFluent $bulkActionFluent): BulkActionFluent
    {
        return $bulkActionFluent;
    }

    abstract public function runAction($model, ActionFields $fields);
}
