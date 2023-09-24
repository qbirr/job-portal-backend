<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NewDatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(SubmissionStatusSeeder::class);
        $this->call(UpdateSettingAddSubscriptionPlanSeeder::class);
    }
}
