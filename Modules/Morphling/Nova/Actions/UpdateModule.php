<?php

namespace Modules\Morphling\Nova\Actions;

use Laravel\Nova\Fields\ActionFields;
use Modules\Morphling\Services\Morphling;
use Modules\Morphling\Utils\BulkActionFluent;

class UpdateModule extends BulkAction
{
    public function runAction($model, ActionFields $fields)
    {
        app(Morphling::class)->update($model);
    }

    protected function getBulkActionOptions(BulkActionFluent $bulkActionFluent): BulkActionFluent
    {
        return $bulkActionFluent->setResourceName('modules');
    }
}
