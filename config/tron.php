<?php


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
