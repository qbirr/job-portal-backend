<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class UpdateSettingAddSubscriptionPlanSeeder extends Seeder {
    public function run(): void {
        Setting::updateOrCreate(['key' => 'enable_subscription_plan'], ['value' => 0]);
    }
}
