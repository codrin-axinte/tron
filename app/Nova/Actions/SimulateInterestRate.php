<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Wallet\Models\PricingPlan;

class SimulateInterestRate extends Action
{
    use InteractsWithQueue, Queueable;

    private bool $isPlan = false;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        /**
         * @var PricingPlan $plan
         */

        $settings = $this->isPlan ? $models->first()->planSettings : $models->first();

        return Action::openInNewTab(route('compound-simulation', [
            'principal' => $fields->get('amount'),
            'days' => $fields->get('days'),
            'rate' => $settings->interest_percentage / 100,
        ]));
    }

    public function isPlan(): static
    {
        $this->isPlan = true;
        return $this;
    }

    /**
     * Get the fields available on the action.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Currency::make(__('Starting Amount'), 'amount')->default(fn() => 100),
            Number::make(__('Days'), 'days')->default(fn() => 365),
        ];
    }
}
