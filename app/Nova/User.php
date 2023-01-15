<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Jeffbeltran\SanctumTokens\SanctumTokens;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\UiAvatar;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Acl\Enums\GenericPermission;
use Modules\Acl\Enums\RolePermission;
use Modules\Acl\Enums\UserPermission;
use Modules\Acl\Nova\Actions\AssignRole;
use Modules\Acl\Nova\Resources\Role;
use Modules\Acl\Services\AclService;

class User extends Resource
{
    use HasTabs;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    public static $with = ['roles'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            UiAvatar::make(__('Avatar'))->resolveUsing(fn () => $this->name ?? implode(' ', explode('@', $this->email))),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            $this->makeRolesField(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            DateTime::make(__('Verified At'), 'email_verified_at')->canSeeWhen(UserPermission::ViewAny->value),

            Tabs::make(__('Details'), [
                MorphedByMany::make(__('Roles'), 'roles', Role::class),
                // TODO: Dynamic Tabs
            ]),

            SanctumTokens::make()->canSeeWhen(GenericPermission::ManageTokens->value),

        ];
    }

    private function makeRolesField(): BooleanGroup
    {
        $roles = $this->model()->getRoleNames();

        $labels = ['none' => __('No Role')];
        $values = ['none' => false];

        if ($roles->isNotEmpty()) {
            $labels = $roles->mapWithKeys(fn (string $role) => [$role => Str::headline($role)])->toArray();
            $values = $roles->mapWithKeys(fn (string $role) => [$role => true])->toArray();
        }

        return BooleanGroup::make(__('Roles'), fn () => $values)
            ->options($labels)
            ->exceptOnForms()
            ->canSeeWhen(RolePermission::ViewAny->value);
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request): array
    {
        return [
            AssignRole::make()
                ->showInline()
                ->canSee(fn () => $request->user()->can(RolePermission::Attach->value)),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query): \Illuminate\Database\Eloquent\Builder
    {
        $user = $request->user();
        $superAdmin = AclService::superAdminRole();

        if ($user->hasRole($superAdmin)) {
            return $query;
        }

        // Filter out super admins
        $ids = $user
            ->with('roles')
            ->role($superAdmin)
            ->pluck('id')
            ->toArray();

        return $query->whereNotIn('id', $ids);
    }
}
