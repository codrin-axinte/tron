<?php

namespace App\Console\Commands;

use Database\Seeders\AfterInstallSeeder;
use Illuminate\Console\Command;

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
                '--seed' => true,
                '--no-interaction' => true,
            ]);
        } else {
            $this->call('migrate', [
                '--seed' => true,
                '--no-interaction' => true,
            ]);
        }

        $this->call('module:seed', [
            '--no-interaction' => true,
        ]);

        $this->call('user:create', [
            '--no-interaction' => true,
        ]);

        $this->call('db:seed', [
            '--no-interaction' => true,
            '--class' => AfterInstallSeeder::class,
        ]);

        $this->comment('Generating open-api specs...');
        $this->call('orion:specs');

        $this->info('Application installed!');

        $this->info('You can now visit the admin panel at: '.route('nova.pages.home'));
        $this->info('Your website is at this url: '.config('app.fronted_url'));
        $this->info('Check out API list at: '.route('swagger.index'));

        return self::SUCCESS;
    }
}
