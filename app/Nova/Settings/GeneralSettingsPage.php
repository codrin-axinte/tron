<?php

namespace App\Nova\Settings;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Panel;
use Modules\Settings\Contracts\SyncEnv;
use Modules\Settings\Pages\Page;
use NormanHuth\SecretField\SecretField;

class GeneralSettingsPage extends Page implements SyncEnv
{
    protected array $fillable = [
        'TELEGRAM_API_KEY',
        'NOVA_LICENSE_KEY',
    ];

    public function fields(): array
    {
        return [
            Boolean::make(__('Is Live'), 'is_live'),

            Panel::make(_('API Keys'), [
                SecretField::make(__('Telegram Bot API Key'), 'TELEGRAM_BOT_KEY'),
                SecretField::make(__('TronGrid API Key'), 'tron_grid_api_key'),
                SecretField::make(__('Nova License Key'), 'NOVA_LICENSE_KEY'),
            ]),
        ];
    }

    public function defaultValues(): array
    {
        return [
            'NOVA_LICENSE_KEY' => env('NOVA_LICENSE_KEY'),
            'TELEGRAM_BOT_KEY' => env('TELEGRAM_API_KEY'),
        ];
    }

    public function getSyncOptions(): array
    {
        return $this->options();
    }
}
