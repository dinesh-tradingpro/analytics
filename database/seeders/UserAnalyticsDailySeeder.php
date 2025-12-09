<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserAnalyticsDailySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ActiveUsersDailySeeder::class,
            InactiveUsersDailySeeder::class,
        ]);
    }
}
