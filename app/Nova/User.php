<?php

namespace App\Nova;

use App\Nova\Actions\TransferTokensAction;
use App\Nova\Filters\FilterByRole;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Validation\Rules;
use Jeffbeltran\SanctumTokens\SanctumTokens;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\UiAvatar;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Acl\Enums\GenericPermission;
use Modules\Acl\Enums\RolePermission;
use Modules\Acl\Enums\UserPermission;
use Modules\Acl\Nova\Resources\Role;
use Modules\Acl\Services\AclService;
use Modules\Wallet\Nova\Resources\PricingPlan;
use Modules\Wallet\Nova\Resources\Wallet;
use Outl1ne\MultiselectField\Multiselect;

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

    public static $with = ['roles', 'wallet'];

    public static $group =  'mlm';

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            UiAvatar::make(__('Avatar'))
                ->resolveUsing(fn () => $this->name ?? implode(' ', explode('@', $this->email))),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Multiselect::make(__('Roles'))
                ->belongsToMany(Role::class)
                ->canSee(fn () => $request->user()->can(RolePermission::Attach->value)),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            DateTime::make(__('Verified At'), 'email_verified_at')
                ->hideFromIndex()
                ->canSeeWhen(UserPermission::ViewAny->value),

            BelongsTo::make(__('Wallet'), 'wallet', Wallet::class)
                ->exceptOnForms(),

            HasOne::make(__('Referral Link'), 'referralLink', ReferralLink::class)->exceptOnForms(),
            BelongsToMany::make(__('Subscribed Plan'), 'pricingPlans', PricingPlan::class),
            HasOne::make(__('Team'), 'ownedTeam', Team::class)->exceptOnForms(),

            SanctumTokens::make()->canSeeWhen(GenericPermission::ManageTokens->value),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            FilterByRole::make(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            TransferTokensAction::make(),
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
