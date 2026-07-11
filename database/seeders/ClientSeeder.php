<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            'name'         => 'أحمد محمد',
            'country_code' => '966',
            'phone'        => '512345678',
            'password'     => 'Password123',
            'image'        => 'default.png',
            'is_active'    => true,
            'is_blocked'   => false,
            'is_notify'    => true,
            'points'       => 0,
        ];

        Client::create($demo);

        for ($i = 2; $i <= 10; $i++) {
            Client::create([
                'name'         => 'عميل ' . $i,
                'country_code' => '966',
                'phone'        => '5' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                'password'     => 'Password123',
                'image'        => 'default.png',
                'is_active'    => true,
                'is_blocked'   => false,
                'is_notify'    => true,
                'points'       => rand(0, 500),
            ]);
        }
    }
}
