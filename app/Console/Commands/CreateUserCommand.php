<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Acl\Services\AclService;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a default super admin user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $role = AclService::superAdminRole();
        $defaultEmail = 'admin@localhost.test';
        $defaultPassword = 'password';

        $generateUser = $this->confirm('Do you want to generate a super user?', true);
        $email = $generateUser ? $defaultEmail : $this->getEmail($defaultEmail);
        $password = $generateUser ? $defaultPassword : $this->getPassword($defaultPassword);

        User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password),
        ])->assignRole($role);

        $this->comment('User created.');

        return Command::SUCCESS;
    }

    private function getEmail($defaultEmail)
    {
        return $this->ask('Email', $defaultEmail);
    }

    private function getPassword($defaultPassword)
    {
        $generatePassword = $this->confirm('Do you want to generate a strong password?', true);

        if ($generatePassword) {
            $password = Str::random();
            $this->comment($password);
        } else {
            $password = $this->secret('Password') ?? $defaultPassword;
        }

        return $password;
    }
}
