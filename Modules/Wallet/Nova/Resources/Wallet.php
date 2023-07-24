<?php

namespace Modules\Wallet\Nova\Resources;

use App\Nova\Actions\TransferTokensAction;
use App\Nova\Resource;
use App\Nova\User;
use App\Nova\WalletTransaction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use NormanHuth\SecretField\SecretField;

class Wallet extends Resource
{
    public static $model = \Modules\Wallet\Models\Wallet::class;

    public static $title = 'user.email';

    public static $search = ['user.email', 'amount'];

    //public static $displayInNavigation = false;

    public static $globallySearchable = false;

    public static $with = ['user'];

    public static $group =  'blockchain';

    public function title(): string
    {
        return $this->amount.' USDT';
    }

    public function subtitle()
    {
        return $this->amount.' | '.$this->blockchain_amount;
    }

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('User'), 'user', User::class)
                ->filterable()
                ->sortable()
                ->required(),

            Text::make(__('Address'), 'address')->nullable(),

            Currency::make(__('Virtual Amount'), 'amount')
                ->displayUsing(fn($amount) => round($amount))
                ->symbol('USD')
                ->filterable()
                ->sortable()
                ->required(),

            Currency::make(__('Blockchain Balance'), 'blockchain_amount')
                ->displayUsing(fn($amount) => round($amount))
                ->symbol('USDT')
                ->filterable()
                ->sortable()
                ->required(),

            DateTime::make(__('Created At'), 'created_at')->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')->onlyOnDetail(),

            Panel::make(__('Security'), [
                SecretField::make(__('Private Key'), 'private_key')->hideFromIndex()->nullable(),
                SecretField::make(__('Public Key'), 'public_key')->hideFromIndex()->nullable(),
                KeyValue::make(__('Mnemonic'), 'mnemonic')->nullable(),
            ]),

            // HasMany::make(__('Transactions'), 'transactions', WalletTransaction::class),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            TransferTokensAction::make(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
}
