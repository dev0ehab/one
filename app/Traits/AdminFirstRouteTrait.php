<?php

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Support\Facades\Route;

trait AdminFirstRouteTrait
{
    public function getAdminFirstRouteName($authRoutes = null)
    {
        $routeName = 'intro';

        if (auth('admin')->check() && auth('admin')->user()->type == 'super_admin') {
            $routeName = 'admin.dashboard';
        }

        if (!$authRoutes) {
            $authRoutes = Permission::where('role_id', auth()->guard('admin')->user()->role_id)
                ->pluck('permission')->toArray();
        }

        $routes = Route::getRoutes('web');

        foreach ($routes as $route) {
            if (
                !in_array($route->getName(), $authRoutes) ||
                empty($route->getAction()['icon']) ||
                str_starts_with($route->getName(), 'admin.all_') ||
                !str_starts_with($route->getName(), 'admin.')
            ) {
                continue;
            }

            $routeName = $route->getName();
            break;
        }

        return $routeName;
    }
}
