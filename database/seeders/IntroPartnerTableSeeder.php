<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntroPartnerTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('intro_partners')->insert([
            ['image'   => '1.png'],
            ['image'  => '3.png'],
            ['image'   => '4.png',]
        ]);
    }
}
