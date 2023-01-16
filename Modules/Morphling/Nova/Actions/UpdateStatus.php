<?php

namespace Modules\Morphling\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class UpdateStatus extends Action
{
    use InteractsWithQueue, Queueable;

    public function __construct(protected string $statusEnum)
    {
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $status = $this->statusEnum::from($fields->get('status'));

        foreach ($models as $model) {
            $model->update(['status' => $status->value]);
        }

        return Action::message('Status updated');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Status')
                ->options($this->statusEnum::options())
                ->required(),
        ];
    }
}
