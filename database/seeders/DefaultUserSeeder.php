<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input = [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@infyjobs.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('123456'),
            'phone' => '7878454512',
            'is_default' => 1,
        ];

        $user = User::create($input);

        $input = [
            'first_name' => 'Admin',
            'last_name' => 'Demo',
            'email' => 'admin@demo.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('demo'),
            'phone' => '8123123123',
            'is_default' => 0,
        ];

        $user = User::create($input);
    }
}
