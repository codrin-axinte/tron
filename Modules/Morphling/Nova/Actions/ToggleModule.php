<?php

namespace Modules\Morphling\Nova\Actions;

use Laravel\Nova\Fields\ActionFields;
use Modules\Morphling\Services\Morphling;
use Modules\Morphling\Utils\BulkActionFluent;

class ToggleModule extends BulkAction
{
    public function name()
    {
        return __('Enable/Disable Module');
    }

    protected function getBulkActionOptions(BulkActionFluent $bulkActionFluent): BulkActionFluent
    {
        return $bulkActionFluent->setResourceName('modules');
    }

    public function runAction($model, ActionFields $fields)
    {
        $morph = app(Morphling::class);

        if ($model->enabled) {
            $morph->disable($model);
        } else {
            $morph->enable($model);
        }
    }
}
