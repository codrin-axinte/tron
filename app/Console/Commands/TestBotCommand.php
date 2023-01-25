<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Models\TelegraphBot;
class TestBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        TelegraphBot::first()->unregisterWebhook(true)->send();

        return Command::SUCCESS;
    }
}
