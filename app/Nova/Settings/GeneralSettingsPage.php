<?php

namespace App\Nova\Settings;

use Laravel\Nova\Fields\Select;
use Modules\Settings\Contracts\SyncEnv;
use Modules\Settings\Pages\Page;
use NormanHuth\SecretField\SecretField;

class GeneralSettingsPage extends Page implements SyncEnv
{

    protected array $fillable = [
        'NOVA_LICENSE_KEY'
    ];

    public function fields(): array
    {
        return [
            SecretField::make(__('Telegram Bot API Key'), 'TELEGRAM_BOT_KEY'),
            SecretField::make(__('TronGrid API Key'), 'tron_grid_api_key'),
            SecretField::make(__('Nova License Key'), 'NOVA_LICENSE_KEY'),

            Select::make(__('Withdraw Method'), 'withdraw_method')->options([
                'approval' => __('Approval'),
                'automatic' => __('Automatic')
            ])->displayUsingLabels(),
        ];
    }

    public function defaultValues(): array
    {
        return [
            'withdraw_method' => 'approval',
            'NOVA_LICENSE_KEY' => env('NOVA_LICENSE_KEY'),
            'TELEGRAM_BOT_KEY' => env('TELEGRAM_BOT_KEY'),
        ];
    }

    public function getSyncOptions(): array
    {
        return $this->options();
    }
}
