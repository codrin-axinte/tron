<?php

namespace Modules\Wallet\Nova\Resources;

use App\Nova\Resource;
use App\Nova\User;
use App\Nova\WalletTransaction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Modules\Payments\Settings\PaymentsSettings;

class Wallet extends Resource
{
    public static string $model = \Modules\Wallet\Models\Wallet::class;

    public static $title = 'amount';

    public static $search = ['user.email', 'amount'];

    public static $displayInNavigation = false;

    public static $globallySearchable = false;

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('User'), 'user', User::class)
                ->filterable()
                ->sortable()
                ->required(),

            Currency::make(__('Amount'), 'amount')
                ->symbol('TRX')
                ->filterable()
                ->sortable()
                ->required(),

            DateTime::make(__('Created At'), 'created_at')->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')->onlyOnDetail(),

            HasMany::make(__('Transactions'), 'transactions', WalletTransaction::class),
        ];
    }

    public function title(): string
    {
        return $this->amount.' TRX';
    }
}
