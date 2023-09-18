<?php

namespace Database\Seeders;

use App\Models\CompanySize;
use Illuminate\Database\Seeder;

class DefaultCompanySizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companySizes = [
            [
                'size' => '5-10',
                'is_default' => 1,
            ],
            [
                'size' => '11-20',
                'is_default' => 1,
            ],
            [
                'size' => '21-50',
                'is_default' => 1,
            ],
            [
                'size' => '51-100',
                'is_default' => 1,
            ],
        ];
        foreach ($companySizes as $companySize) {
            CompanySize::create($companySize);
        }
    }
}
