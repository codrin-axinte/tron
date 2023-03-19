<?php

namespace Modules\Morphling\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Morphling\Services\Morphling;

class InstallModule extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $module = $fields->get('module');

        try {
            $this->morphling()->install($module);
        } catch (\Exception $exception) {
            return Action::danger($exception->getMessage());
        }

        return Action::message($module);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $modules = $this->morphling()->fetchModulesFromRepository();

        return [
            Select::make('Module')
                ->options($modules)
                ->searchable()
                ->required(),
        ];
    }

    private function morphling(): Morphling
    {
        return app(Morphling::class);
    }
}
