<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {

        $routes_data = [];

        foreach (Route::getRoutes() as $route) {
            if ($route->getName()) {
                $routes_data[] = ['role_id' => 1, 'permission' => $route->getName()];
            }
        }

        Permission::insert($routes_data);
    }
}
