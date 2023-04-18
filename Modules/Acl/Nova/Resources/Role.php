<?php

namespace Modules\Acl\Nova\Resources;

use App\Nova\Resource;
use App\Nova\User;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Acl\Enums\BasePermission;
use Modules\Acl\Enums\RolePermission;
use Modules\Acl\Nova\Actions\AttachPermissions;
use Outl1ne\MultiselectField\Multiselect;

class Role extends Resource
{
    use HasTabs;

    public static string $model = \Spatie\Permission\Models\Role::class;

    public static $displayInNavigation = false;

    public static $title = 'name';

    public static $search = [
        'id', 'name',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name'),
            Text::make('Guard Name'),
            Multiselect::make(__('Permissions'))
                ->belongsToMany(Permission::class)
                ->canSee(fn () => $request->user()->can(RolePermission::Attach->value)),

            Tabs::make(__('Relations'), [
                //  BelongsToMany::make('Permissions', 'permissions', Permission::class)->searchable()->showCreateRelationButton(),
                BelongsToMany::make('Users', 'users', User::class)->searchable()->showCreateRelationButton(),
            ]),
        ];
    }

    public function authorizedTo(Request $request, $ability): bool
    {
        return $request->user()->can('roles.'.$ability);
    }

    public function actions(NovaRequest $request): array
    {
        return [
            AttachPermissions::make()->canSeeWhen(BasePermission::Attach->value),
        ];
    }
}
