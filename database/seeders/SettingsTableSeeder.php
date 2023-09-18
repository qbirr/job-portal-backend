<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imageUrl = 'assets/img/infyom-logo.png';
        $favicon = 'favicon.ico';
        $input = [
            ['key' => 'application_name', 'value' => 'InfyOmLabs'],
            ['key' => 'logo', 'value' => $imageUrl],
            ['key' => 'favicon', 'value' => $favicon],
            ['key' => 'company_description', 'value' => 'Leading Laravel Development Company of India'],
            ['key' => 'address', 'value' => '446, Tulsi Arcade, Nr. Sudama Chowk, Mota Varachha, Surat - 394101, Gujarat, India'],
            ['key' => 'phone', 'value' => '+91 70963 36561'],
            ['key' => 'email', 'value' => 'contact@infyom.in'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/infyom/'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/infyom?lang=en'],
            ['key' => 'google_plus_url', 'value' => 'https://infyom.com/'],
            ['key' => 'linkedIn_url',
                'value' => 'https://www.linkedin.com/organization-guest/company/infyom-technologies?challengeId=AQFgQaMxwSxCdAAAAXOA_wosiB2vYdQEoITs6w676AzV8cu8OzhnWEBNUQ7LCG4vds5-A12UIQk1M4aWfKmn6iM58OFJbpoRiA&submissionId=0088318b-13b3-2416-9933-b463017e531e',
            ],
            ['key' => 'about_us', 'value' => 'Over past 10+ years of experience and skills in various technologies, we built great scalable products.
Whatever technology we worked with, we just not build products for our clients but we also try to make it generate and available to other lots of developers worldwide. And that\'s the reason we are the only leading company in India who created tools and available it to millions of developers worldwide. Feel free to checkout our Github account. Because we believe that open-source is the future !! We have an open-source lab to which we call InfyOm Lab .'],
        ];

        foreach ($input as $data) {
            $key = Setting::where('key', $data['key'])->first();
            if (isset($key)) {
                $key->update($data);
            } else {
                Setting::create($data);
            }
        }
    }
}
