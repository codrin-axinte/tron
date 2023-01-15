<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Modules\Acl\Utils\AclBuilder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class AfterInstallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws FileCannotBeAdded
     */
    public function run(): void
    {
        // $locale = app()->getLocale();
        // $this->faker = fake($locale);
        // $carbon = Carbon::setLocale($locale);
        //$acl = new AclBuilder();
    }
}
