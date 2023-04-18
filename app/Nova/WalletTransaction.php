<?php

namespace App\Nova;

use App\Nova\Traits\ResourceIsReadonly;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Nova\Resources\Wallet;

class WalletTransaction extends Resource
{
    use ResourceIsReadonly;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\WalletTransaction>
     */
    public static string $model = \Modules\Wallet\Models\WalletTransaction::class;

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
        'id', 'description', 'type',
    ];

    public static $displayInNavigation = false;

    public static $with = ['author', 'wallet'];

    public function title(): string
    {
        return $this->amount.' '.$this->type->name;
    }

    public function subtitle()
    {
        return $this->author->email;
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Owner'), 'author', User::class)
                ->filterable()
                ->sortable()
                ->searchable(),
            BelongsTo::make(__('Wallet'), 'wallet', Wallet::class)
                ->sortable()
                ->filterable()
                ->searchable(),

            Currency::make(__('Amount'), 'amount')
                ->symbol('TRX')
                ->filterable()
                ->sortable(),

            Select::make(__('Type'), 'type')
                ->options(WalletTransactionType::options(true))
                ->sortable()
                ->filterable()
                ->required(),

            Textarea::make(__('Description'), 'description')
                ->alwaysShow(),

            DateTime::make(__('Created At'), 'created_at'),
            DateTime::make(__('Updated At'), 'updated_at')->onlyOnDetail(),

            KeyValue::make(__('Meta'), 'meta')->nullable(),
        ];
    }
}
