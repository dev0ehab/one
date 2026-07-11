<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
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
                'name' => json_encode(['ar' => 'السعودية', 'en' => 'Saudi Arabia']),
                'code' => '966',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('countries')->insert($data);
    }
}
