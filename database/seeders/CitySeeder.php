<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => json_encode(['ar' => 'الرياض', 'en' => 'Riyadh']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'جدة', 'en' => 'Jeddah']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'مكة المكرمة', 'en' => 'Makkah']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'المدينة المنورة', 'en' => 'Madinah']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'الدمام', 'en' => 'Dammam']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'الخبر', 'en' => 'Khobar']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'الطائف', 'en' => 'Taif']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'بريدة', 'en' => 'Buraydah']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'عنيزة', 'en' => 'Unaizah']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'حائل', 'en' => 'Hail']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'تبوك', 'en' => 'Tabuk']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'أبها', 'en' => 'Abha']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'خميس مشيط', 'en' => 'Khamis Mushait']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'جازان', 'en' => 'Jazan']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'نجران', 'en' => 'Najran']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'ينبع', 'en' => 'Yanbu']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'الجبيل', 'en' => 'Jubail']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'الاحساء', 'en' => 'Al Ahsa']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'بيشة', 'en' => 'Bisha']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['ar' => 'سكاكا', 'en' => 'Sakaka']),
                'country_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('cities')->insert($data);
    }
}
