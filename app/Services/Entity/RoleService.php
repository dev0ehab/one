<?php

namespace App\Services\Entity;

use App\Models\Permission;
use App\Models\Role;
use App\Traits\ReportTrait;
use Illuminate\Support\Facades\Route;

class RoleService extends BaseService
{
    /**
     * Initializes the IntroService class.
     *
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    public function create()
    {
        $routes = Route::getRoutes();
        $routes_data = [];
        foreach ($routes as $route) {
            if ($route->getName()) {
                $routes_data['"' . $route->getName() . '"'] = ['title' => isset($route->getAction()['title']) ? $route->getAction()['title'] : null];
            }
        }
        return view('admin.roles.create', get_defined_vars());
    }

    public function store($request)
    {
        if (!$request->permissions) {
            abort(422, __('admin.select_at_least_one_permission'));
        }

        $role = Role::create($request->validated());

        $permissions = [];
        foreach ($request->permissions ?? [] as $permission) {
            $permissions[]['permission'] = $permission;
        }

        $role->permissions()->createMany($permissions);
        ReportTrait::addToLog('add_' . $role->name . '_role');

        return ['status' => 'success', 'url' => route('admin.roles.index')];
    }

    public function edit($id)
    {
        $role        = Role::findOrFail($id);
        $routes      = Route::getRoutes();
        $routes_data = [];
        $my_routes   = Permission::where('role_id', $id)->pluck('permission')->toArray();

        foreach ($routes as $route) {
            if ($route->getName()) {
                $routes_data['"' . $route->getName() . '"'] = ['title' => isset($route->getAction()['title']) ? $route->getAction()['title'] : null];
            }
        }
        return view('admin.roles.edit', get_defined_vars());
    }

    public function update($request, $id)
    {
        if (!$request->permissions) {
            abort(422, __('admin.select_at_least_one_permission'));
        }

        $role = Role::findOrFail($id);
        $role->update($request->validated());

        $role->permissions()->delete();
        $permissions = [];

        foreach ($request->permissions ?? [] as $permission) {
            $permissions[]['permission'] = $permission;
        }

        $role->permissions()->createMany($permissions);
        ReportTrait::addToLog('edit_' . $role->name . '_role');

        return ['status' => 'success', 'url' => route('admin.roles.index')];
    }
}
