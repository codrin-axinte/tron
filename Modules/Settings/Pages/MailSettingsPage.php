<?php

namespace Modules\Settings\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Modules\Settings\Contracts\SyncEnv;
use Modules\Settings\Enums\SettingsPermission;
use NormanHuth\SecretField\SecretField;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class MailSettingsPage extends Page implements SyncEnv
{
    protected array $fillable = [
        'mail_username',
        'mail_password',
        'mail_from_name',
        'mail_from_address',
        'mail_host',
        'mail_port',
        'mail_encryption',
        'driver_options',
    ];

    protected array $guarded = ['*'];

    public function fields(): array
    {
        return [

            Panel::make('Branding', [
                Text::make('From Address', 'mail_from_address')->required()->rules(['email']),
                Text::make('From Name', 'mail_from_name')->required()->rules('string'),
            ]),

            Panel::make('Server', [
                Select::make('Driver', 'mail_mailer')
                    ->options(
                        fn () => collect(config('mail.mailers', []))
                            ->mapWithKeys(fn ($value, $key) => [$key => Str::headline($key)])
                    )
                    ->required(),

                Text::make('Host', 'mail_host')->required(),
                Number::make('Port', 'mail_port')->required()->rules([
                    'numeric',
                    'min:0',
                    'max:65535',
                ]),
                Select::make('Encryption', 'mail_encryption')
                    ->options([
                        '' => 'None',
                        'tls' => 'TLS',
                        'ssl' => 'SSL',
                    ])
                    ->nullable(),
                Text::make('Username', 'mail_username')->nullable(),
                SecretField::make('Password', 'mail_password')->disableClipboard(),

                // Flexible::make('Driver Options', 'driver_options')->preset(EnvOptionsPreset::class),
            ]),
        ];
    }

    public function casts(): array
    {
        return [
            'driver_options' => FlexibleCast::class,
            'mail_password' => 'encrypted',
        ];
    }

    public function authorize(Request $request): bool
    {
        return $request->user()->can(SettingsPermission::UpdateSystem->value);
    }

    public function defaultValues(): array
    {
        return [
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            'mail_mailer' => config('mail.default'),
            'mail_host' => env('MAIL_HOST'),
            'mail_port' => env('MAIL_PORT'),
            'mail_encryption' => env('MAIL_ENCRYPTION'),
            'mail_username' => env('MAIL_USERNAME'),
            'mail_password' => env('MAIL_PASSWORD'),
        ];
    }

    #[Pure]
    public function getSyncOptions(): array
    {
        return $this->options();
    }
}
