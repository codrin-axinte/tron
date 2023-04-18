<?php


use App\Telegram\Commands\{
    Admin\AdminToolboxCommand,
    Admin\SandboxCommand,
    DummyCommand,
    HelpCommand,
    JoinCommand,
    MeCommand,
    PackagesCommand,
    ShowReferralCodeCommand,
    ShowWalletCommand,
    StartCommand,
    TeamCommand,
    TradeCommand,
    WithdrawCommand
};

return [


    'default_role' => 'trader',

    /**
     *
     */
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
