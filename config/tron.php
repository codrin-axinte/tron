<?php

use App\Telegram\Commands\Admin\AdminToolboxCommand;
use App\Telegram\Commands\Admin\SandboxCommand;
use App\Telegram\Commands\DummyCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\JoinCommand;
use App\Telegram\Commands\MeCommand;
use App\Telegram\Commands\PackagesCommand;
use App\Telegram\Commands\ShowReferralCodeCommand;
use App\Telegram\Commands\ShowWalletCommand;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Commands\TeamCommand;
use App\Telegram\Commands\TradeCommand;
use App\Telegram\Commands\WithdrawCommand;

return [

    'default_role' => 'trader',

    // 'compound_interest_update_action' => \App\Actions\MLM\UpdateUsersWalletsByTradingPlan::class,

    /**
     * How many pools should be created on installation.
     * One of them will be set as central pool.
     */
    'pools' => 1,

    /**
     * Allowed telegram commands.
     * You can add or remove any of them,
     * but with care since it can break the flow
     */
    'telegram_commands' => [
        'dummy' => DummyCommand::class,
        'help' => HelpCommand::class,
        'join' => JoinCommand::class,
        'team' => TeamCommand::class,
        'myCode' => ShowReferralCodeCommand::class,
        'test' => SandboxCommand::class,
        'start' => StartCommand::class,
        'wallet' => ShowWalletCommand::class,
        'me' => MeCommand::class,
        'admin' => AdminToolboxCommand::class,
        'packages' => PackagesCommand::class,
        'trade' => TradeCommand::class,
        'withdraw' => WithdrawCommand::class,
    ],
];
