<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use App\Models\Permission;

trait  SideBarTrait
{

    /**
     * Render the sidebar view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    static function sidebar()
    {
        // Get the permissions for the current admin user
        $my_routes = Permission::where('role_id', auth()->guard('admin')->user()->role_id)
            ->pluck('permission')
            ->toArray();

        // Get all routes
        $routes = Route::getRoutes('web');

        // Determine the routes data based on the user type
        $routes_data = auth('admin')->check() && auth('admin')->user()->type == 'super_admin' 
            ? self::superAdminRoutes($routes)
            : self::authAdminRoutes($my_routes, $routes);



        // Return the sidebar view with the necessary data
        return view('admin.shared.sidebar.sidebar', [
            'my_routes'   => $my_routes,
            'routes_data' => $routes_data,
        ]);
    }

    /**
     * Filter and retrieve data from admin routes.
     *
     * @param array $my_routes The routes to be filtered.
     * @param array $routes The routes to be compared against.
     * @return array The filtered routes data.
    */
    static function authAdminRoutes($my_routes, $routes)
    {
        $routes_data = [];

        // Iterate through each route
        foreach ($routes as $route) {
            // Check if the route has a name and is in the filtered routes
            if ($route->getName() && in_array($route->getName(), $my_routes) && isset($route->getAction()['title']) ) {
                // Add the route data to the filtered routes data array
                $routes_data['"' . $route->getName() . '"'] = [
                    'routeName'     => $route->getName(),
                    'sub_link'      => isset($route->getAction()['sub_link']) ? $route->getAction()['sub_link'] : false,
                    'child'         => isset($route->getAction()['child']) && count($route->getAction()['child']) ? $route->getAction()['child'] : false,
                    'has_sub_route' => isset($route->getAction()['has_sub_route']) ? $route->getAction()['has_sub_route'] : false,
                    'type'      => isset($route->getAction()['type']) ? $route->getAction()['type'] : null,
                    'title'     => $route->getAction()['title'],
                    'icon'      => isset($route->getAction()['icon']) ? $route->getAction()['icon'] : null,
                    'name'      => $route->getName(),
                ];
            }
        }

        return $routes_data;
    }

    /**
     * Extracts super admin routes data.
     *
     * @param array $routes The array of routes.
     *
     * @return array The extracted super admin routes data.
     */
    static function superAdminRoutes($routes)
    {
        // Create an empty array to store the extracted routes data
        $routes_data = [];

        // Iterate through each route
        foreach ($routes as $route) {
            // Check if the route has a name and a title in its action
            if ($route->getName() && isset($route->getAction()['title'])) {
                // Extract the route data and add it to the routes data array
                $routes_data['"' . $route->getName() . '"'] = [
                    'routeName'     => $route->getName(),
                    'sub_link'      => isset($route->getAction()['sub_link']) ? $route->getAction()['sub_link'] : false,
                    'child'         => isset($route->getAction()['child']) && count($route->getAction()['child']) ? $route->getAction()['child'] : false,
                    'has_sub_route' => isset($route->getAction()['has_sub_route']) ? $route->getAction()['has_sub_route'] : false,
                    'type'          => isset($route->getAction()['type']) ? $route->getAction()['type'] : null,
                    'title'         => $route->getAction()['title'],
                    'icon'          => isset($route->getAction()['icon']) ? $route->getAction()['icon'] : null,
                ];
            }
        }

        // Return the extracted routes data
        return $routes_data;
    }
}
