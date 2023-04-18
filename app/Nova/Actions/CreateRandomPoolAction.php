<?php

namespace App\Nova\Actions;

use App\Services\PoolManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreateRandomPoolAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $standalone = true;

    public function name()
    {
        return __('Create random pool');
    }

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $amount = $fields->get('amount', 1);

        app(PoolManager::class)->createRandom($amount);

        return Action::message('Pools created');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make(__('Amount'), 'amount')->default(fn () => 1),
        ];
    }
}
