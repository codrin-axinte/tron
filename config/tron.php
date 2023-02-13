<?php


use App\Actions\MLM\UpdateUsersWalletsBySubscribedPlan;
use App\Telegram\Commands\Admin\AdminToolboxCommand;
use App\Telegram\Commands\Admin\SandboxCommand;
use App\Telegram\Commands\DummyCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\JoinCommand;
use App\Telegram\Commands\MeCommand;
use App\Telegram\Commands\ShowReferralCodeCommand;
use App\Telegram\Commands\ShowWalletCommand;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Commands\TeamCommand;

return [


    'default_role' => 'trader',

    /**
     *
     */
    'compound_interest_update_action' => UpdateUsersWalletsBySubscribedPlan::class,

    /**
     * How many pools should be created on installation.
     * One of them will be set as central pool.
     */
    'pools' => 5,

    /**
     * Allowed telegram commands.
     * You can add or remove any of them,
     * but with care since it can break the flow
     */
    'telegram_commands' => [
        'dummy' => DummyCommand::class,
        'help' => HelpCommand::class,
        'join' => JoinCommand::class,
        //  'packages' => PackagesCommand::class,
        // 'upgrade' => UpgradePackageCommand::class,
        'team' => TeamCommand::class,
        'myCode' => ShowReferralCodeCommand::class,
        'test' => SandboxCommand::class,
        'start' => StartCommand::class,
        'wallet' => ShowWalletCommand::class,
        'me' => MeCommand::class,
        'admin' => AdminToolboxCommand::class,
    ],
];
