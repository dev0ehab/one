<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Traits\ResponseTrait;
use App\Services\Entity\AdminService;

class AdminController extends BaseController
{
    use ResponseTrait;

    public function __construct(AdminService $service)
    {
        parent::__construct($service);
        view()->share('roles', Role::query()->latest()->get());
    }

    public function notifications()
    {
        return $this->service->notifications();
    }

    public function deleteNotifications()
    {
        return $this->service->deleteNotifications($this->request);
    }

    public function block()
    {
        return $this->service->block($this->request);
    }
}
