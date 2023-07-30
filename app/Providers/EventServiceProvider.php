<?php

namespace App\Providers;

use App\Events\TelegramHook;
use App\Events\TokenTransferFailed;
use App\Events\TokenTransferSuccessful;
use App\Events\TransactionApproved;
use App\Events\TransactionRejected;
use App\Events\TransactionStatusUpdated;
use App\Events\UserJoined;
use App\Listeners\ActivateUser;
use App\Listeners\NotifyTransactionStatusUpdated;
use App\Listeners\PayCommissions;
use App\Listeners\RegisterAppSettings;
use App\Listeners\SendTemplateMessage;
use App\Listeners\UpdateBlockchainWallet;
use App\Listeners\UpdateWalletAmount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Settings\Events\BootSettingsPage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        BootSettingsPage::class => [
            RegisterAppSettings::class,
        ],

        TelegramHook::class => [
            SendTemplateMessage::class,
        ],

        UserJoined::class => [
            SendTemplateMessage::class,
        ],

        TokenTransferSuccessful::class => [
            UpdateBlockchainWallet::class,
            ActivateUser::class,
        ],

        TokenTransferFailed::class => [
            // Send notifications? Report the issue?
        ],

        TransactionStatusUpdated::class => [
            //UpdateWalletAmount::class,
            NotifyTransactionStatusUpdated::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
