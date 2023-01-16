<?php

namespace Modules\Morphling\Nova\Actions;

use Laravel\Nova\Fields\ActionFields;
use Modules\Morphling\Services\Morphling;
use Modules\Morphling\Utils\BulkActionFluent;

class DeleteModule extends BulkAction
{
    protected function getBulkActionOptions(BulkActionFluent $bulkActionFluent): BulkActionFluent
    {
        return $bulkActionFluent
            ->setResourceName('modules')
            ->setActionName('delete');
    }

    public function runAction($model, ActionFields $fields)
    {
        app(Morphling::class)->uninstall($model);
    }
}
