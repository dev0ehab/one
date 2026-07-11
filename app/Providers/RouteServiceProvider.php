<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';
    protected $adminNamespace = 'App\Http\Controllers\Admin';
    protected $siteNamespace  = 'App\Http\Controllers\Site';
    protected $apiNamespace  = 'App\Http\Controllers\Api';

    public const HOME = '/home';

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        $this->mapAdminDashboardRoutes();

        $this->mapWebSiteRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::prefix('admin')
            ->middleware(['web', 'web-cors', 'admin-lang'])
            ->name('admin.')
            ->namespace($this->adminNamespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapAdminDashboardRoutes()
    {
        // The dashboard route files must be prefixed with a timestamp to ensure the correct order
        // according the default order of "Base Dashboard" and our vision (e.g. 2025_01_01_0001_foo.php)
        $routesPath = base_path('routes/dashboard/');

        $routeFiles = array_diff(scandir($routesPath), ['.', '..']);

        $fullPaths = array_map(function ($file) use ($routesPath) {
            return $routesPath . $file;
        }, $routeFiles);

        Route::prefix('admin')
            ->middleware(['web', 'web-cors', 'admin-lang', 'admin'])
            ->name('admin.')
            ->namespace($this->adminNamespace)
            ->group(array_values($fullPaths));
    }

    protected function mapApiRoutes()
    {
        $routesPath = base_path('routes/api/');

        $routeFiles = array_diff(scandir($routesPath), ['.', '..']);

        $fullPaths = array_map(function ($file) use ($routesPath) {
            return $routesPath . $file;
        }, $routeFiles);

        $fullPaths = array_merge($fullPaths, [base_path('routes/api.php')]);

        Route::prefix('api')
            ->middleware(['api', 'api-cors', 'api-lang'])
            ->namespace($this->apiNamespace)
            ->group(array_values($fullPaths));
    }

    protected function mapWebSiteRoutes()
    {
        Route::middleware(['web', 'HtmlMinifier'])
            ->namespace($this->siteNamespace)
            ->group(base_path('routes/site.php'));
    }
}
