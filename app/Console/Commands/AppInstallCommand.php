<?php

namespace App\Console\Commands;

use App\Actions\Installation\AfterInstallAction;
use App\Actions\Installation\CreateTelegraphBot;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Pipeline\Pipeline;

class AppInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the application.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (app()->environment('local')) {
            $this->call('migrate:fresh', [
                '--no-interaction' => true,
            ]);
        } else {
            $this->call('migrate', [
                '--no-interaction' => true,
            ]);
        }

        $this->call('module:seed', [
            '--no-interaction' => true,
        ]);

        $this->call('user:create', [
            '--no-interaction' => true,
        ]);

        $payload = [
            'user' => User::latest()->first(),
        ];

        app(Pipeline::class)
            ->send($payload)
            ->through([
                CreateTelegraphBot::class,
                AfterInstallAction::class,
            ])
            ->thenReturn();


        $this->comment('Generating open-api specs...');
        $this->call('orion:specs');

        $this->info('Application installed!');

        $this->info('You can now visit the admin panel at: ' . route('nova.pages.home'));
        $this->info('Your website is at this url: ' . config('app.frontend_url'));
        $this->info('Check out API list at: ' . route('swagger.index'));

        return self::SUCCESS;
    }
}
