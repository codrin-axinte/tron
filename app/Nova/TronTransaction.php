<?php

namespace App\Nova;

use App\Enums\TransactionStatus;
use App\Nova\Actions\ApproveTransaction;
use App\Nova\Actions\RejectTransaction;
use App\Nova\Traits\ResourceIsReadonly;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TronTransaction extends Resource
{
    use ResourceIsReadonly;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\TronTransaction>
     */
    public static $model = \App\Models\TronTransaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'from', 'to', 'contract', 'blockchain_reference_id'
    ];

    public static $group = 'blockchain';

    public static $polling = true;

    public static $pollingInterval = 15;

    public static $showPollingToggle = true;


    public static function label()
    {
        return __('Transactions');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            //ID::make()->sortable(),
            Text::make(__('Status'), 'status')
                ->filterable(),

            Text::make(__('From'), 'from')
                ->sortable()
                ->filterable(),

            Text::make(__('To'), 'to')
                ->sortable()
                ->filterable(),

            Currency::make(__('Amount'), 'amount')
                ->displayUsing(fn($amount) => round($amount))
                ->symbol('USDT')
                ->filterable()
                ->sortable()
                ->required(),

            Text::make(__('Reference'), 'blockchain_reference_id')
                ->filterable()
                ->hideFromIndex(),


            Text::make(__('Contract'), 'contract')
                ->filterable()
                ->hideFromIndex(),

            DateTime::make('created_at')->filterable()->sortable(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            ApproveTransaction::make()
                ->showInline()
                ->canRun(
                    fn(NovaRequest $request) => $request->model()->status === TransactionStatus::AwaitingConfirmation
                ),

            RejectTransaction::make()
                ->showInline()
                ->canRun(
                    fn(NovaRequest $request) => $request->model()->status === TransactionStatus::AwaitingConfirmation
                ),
        ];
    }


}
