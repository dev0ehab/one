<?php

namespace Database\Seeders;

use App\Traits\ImageFakerTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SocialTableSeeder extends Seeder
{
    use ImageFakerTrait;

    public function run()
    {
        $this->createImageWithName("socials", "default");


        DB::table('socials')->insert([
            [
                'icon' => 'default.png',
                'name' => 'facebook',
                'link' => 'https://www.facebook.com',
            ],
            [
                'icon' => 'default.png',
                'name' => 'instgram',
                'link' => 'https://www.instgram.com',
            ],
            [
                'name' => 'whatsapp',
                'icon' => 'default.png',
                'link' => 'https://www.whatsapp.com',
            ]
        ]);
    }
}
