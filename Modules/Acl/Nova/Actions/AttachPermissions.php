<?php

namespace Modules\Acl\Nova\Actions;

use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Acl\Nova\Resources\Permission;
use Modules\Morphling\Nova\Actions\BulkAction;
use Modules\Morphling\Utils\BulkActionFluent;
use Outl1ne\MultiselectField\Multiselect;

class AttachPermissions extends BulkAction
{
    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Multiselect::make(__('Permissions'), 'permissions')
                ->asyncResource(Permission::class),

        ];
    }

    protected function getBulkActionOptions(BulkActionFluent $bulkActionFluent): BulkActionFluent
    {
        return $bulkActionFluent
            ->setResourceName('permissions')
            ->setActionName('attached');
    }

    public function runAction($model, ActionFields $fields)
    {
        $model->givePermissionTo($fields->get('permissions'));
    }
}
