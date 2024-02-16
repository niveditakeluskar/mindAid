<?php

use Illuminate\Database\Seeder;
use RCare\Core\Database\Seeders\DatabaseSeeder as CoreDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CoreDatabaseSeeder::class);
    }
}
