<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (! app()->environment('local')) {
            return;
        }

        /*User::factory()->create([
            'email' => 'admin@localhost.test',
        ]);*/

        // \App\Models\User::factory(10)->create();
    }
}
