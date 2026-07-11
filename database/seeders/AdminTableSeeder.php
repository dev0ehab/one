<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name'     => 'Manager',
            'email'    => 'admin@admin.com',
            'phone'    => '0555105813',
            'password' => 123456,
            'type'     => 'super_admin',
            'role_id'  => 1,
        ]);
    }
}
