<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'language' => 'English',
                'iso_code' => 'eng',
                'is_default' => 1,
            ],
            [
                'language' => 'French',
                'iso_code' => 'fre (B)',
                'is_default' => 1,
            ],
            [
                'language' => 'German',
                'iso_code' => 'ger (B)',
                'is_default' => 1,
            ],
            [
                'language' => 'Gujarati',
                'iso_code' => 'guj',
                'is_default' => 1,
            ], [
                'language' => 'Hindi',
                'iso_code' => 'hin',
                'is_default' => 1,
            ],
            [
                'language' => 'Italian',
                'iso_code' => 'ita',
                'is_default' => 1,
            ], [
                'language' => 'Nepali',
                'iso_code' => 'nep',
                'is_default' => 1,
            ], [
                'language' => 'Russian',
                'iso_code' => 'rus',
                'is_default' => 1,
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
