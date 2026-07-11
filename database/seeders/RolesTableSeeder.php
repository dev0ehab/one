<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['name' => json_encode(['ar' => 'ادمن', 'en' => 'admin'])]
        ]);
        
    }
}
