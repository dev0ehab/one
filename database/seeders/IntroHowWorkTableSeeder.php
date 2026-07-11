<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntroHowWorkTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('intro_how_works')->insert([
            [
                'title' => json_encode(['ar' => 'حمل التطبيق', 'en'  => 'uploade app'], JSON_UNESCAPED_UNICODE),
                'image' => '11.png',
            ],
            [
                'title' => json_encode(['ar' => 'تسجيل مستخدم جديد', 'en'  => 'Register New User'], JSON_UNESCAPED_UNICODE),
                'image' => '22.png',
            ],
            [
                'title' => json_encode(['ar' => 'أدخل رمز التحقق لتفعيل الحساب', 'en'  => 'Send Activation Code To activate account'], JSON_UNESCAPED_UNICODE),
                'image' => '33.png',
            ],
            [
                'title' => json_encode(['ar' => 'حدد مكان إقامتك على الخريطة', 'en'  => 'Locate Your location'], JSON_UNESCAPED_UNICODE),
                'image' => '44.png',
            ]
        ]);
    }
}
