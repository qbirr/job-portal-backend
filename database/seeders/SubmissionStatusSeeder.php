<?php

namespace Database\Seeders;

use App\Models\SubmissionStatus;
use Illuminate\Database\Seeder;

class SubmissionStatusSeeder extends Seeder {
    public function run(): void {
        $statuses = [
            [
                'status_name' => 'pending',
            ],
            [
                'status_name' => 'approved',
            ],
            [
                'status_name' => 'rejected',
            ],
            [
                'status_name' => 'resubmit',
            ],
        ];

        foreach ($statuses as $status)
            SubmissionStatus::create($status);
    }
}
