<?php

namespace Modules\Wallet\Nova\Resources;

use App\Nova\Actions\SimulateInterestRate;
use App\Nova\PricingPlanSettings;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Modules\Payments\Settings\PaymentsSettings;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class PricingPlan extends Resource
{
    use HasSortableRows;

    public static string $model = \Modules\Wallet\Models\PricingPlan::class;

    public static $title = 'name';

    public static $displayInNavigation = false;

    public static $search = [
        'name', 'description', 'features',
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->help(__('A unique and descriptive name.'))
                ->placeholder(__('Gold Package'))
                ->translatable()
                ->required(),

            Currency::make(__('Price'), 'price')
                ->placeholder('5.00')
                ->symbol('TRX')
                ->required(),

            Textarea::make(__('Description'), 'description')
                ->help(__('A short text that describes to whom is best suited this plan.'))
                ->placeholder(__('This plan is best suited for startups that want to try out our product.'))
                ->translatable()
                ->nullable()
                ->alwaysShow()
                ->rows(2),

            Boolean::make(__('Is Best'), 'is_best'),
            Boolean::make(__('Enabled'), 'enabled'),

            Panel::make(__('Features List'), [
                SimpleRepeatable::make(__('Features'), 'features', [
                    Text::make(__('Feature'), 'name')->required(),
                    Textarea::make(__('Summary'), 'summary')->nullable(),
                ]),
            ]),

            HasOne::make('Plan Settings', 'planSettings',PricingPlanSettings::class)
                ->hideWhenCreating()
        ];
    }


    public function actions(NovaRequest $request)
    {
        return [
            SimulateInterestRate::make()
                ->isPlan()
                ->exceptOnIndex()
                ->showInline()
                ->showOnDetail(),
        ];
    }
}
