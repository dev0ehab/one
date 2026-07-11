<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntroSliderTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('intro_sliders')->insert([
            [
                'image'       => '1.png',
                'title'       => json_encode(['ar' => 'عنوان البانر الاول', 'en' => 'First banner title '], JSON_UNESCAPED_UNICODE),
                'description' => json_encode(['ar' => ' وسف البانر الاول', 'en' => 'first banner description'], JSON_UNESCAPED_UNICODE),
            ],
            [
                'image'       => '2.png',
                'title'       => json_encode(['ar' => 'عنوان البانر الثاني', 'en' => 'secound banner title '], JSON_UNESCAPED_UNICODE),
                'description' => json_encode(['ar' => ' وسف البانر الثاني', 'en' => 'secound banner description'], JSON_UNESCAPED_UNICODE),
            ],
            [
                'image'       => '1.png',
                'title'       => json_encode(['ar' => 'عنوان البانر الثالث', 'en' => 'third banner title '], JSON_UNESCAPED_UNICODE),
                'description' => json_encode(['ar' => ' وسف البانر الثالث', 'en' => 'third banner description'], JSON_UNESCAPED_UNICODE),
            ],
            [
                'image'       => '2.png',
                'title'       => json_encode(['ar' => 'عنوان البانر الرابع', 'en' => 'fourth banner title '], JSON_UNESCAPED_UNICODE),
                'description' => json_encode(['ar' => ' وسف البانر الرابع', 'en' => 'fourth banner description'], JSON_UNESCAPED_UNICODE),
            ]
        ]);
    }
}
