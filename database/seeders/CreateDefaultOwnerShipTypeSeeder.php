<?php

namespace Database\Seeders;

use App\Models\OwnerShipType;
use Illuminate\Database\Seeder;

class CreateDefaultOwnerShipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ownerTypes = [
            /*[
                'name' => 'Sole Proprietorship',
                'description' => 'The sole proprietorship is the simplest business form under which one can operate a business.',
                'is_default' => 1,
            ],
            [
                'name' => 'Public',
                'description' => 'A company whose shares are traded freely on a stock exchange.',
                'is_default' => 1,
            ],
            [
                'name' => 'Private',
                'description' => 'A company whose shares may not be offered to the public for sale and which operates under legal requirements less strict than those for a public company.',
                'is_default' => 1,
            ],*/
            [
                'name' => 'Government',
                'description' => 'A government company is a company in which 51% or more of the paid-up capital is held by the Government or State Government.',
                'is_default' => 1,
            ],
            [
                'name' => 'Semi Government',
                'description' => 'TBD',
                'is_default' => 1,
            ],
            [
                'name' => 'Private',
                'description' => 'TBD',
                'is_default' => 1,
            ],
            [
                'name' => 'Non-Profit',
                'description' => 'TBD',
                'is_default' => 1,
            ],
            [
                'name' => 'Personal / Family',
                'description' => 'TBD',
                'is_default' => 1,
            ],
        ];

        foreach ($ownerTypes as $ownerType) {
            OwnerShipType::create($ownerType);
        }
    }
}
