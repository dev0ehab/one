<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\RoleService;

class RoleController extends BaseController
{
    public function __construct(RoleService $service)
    {
        parent::__construct(
            $service,
            [
                'store' => \App\Http\Requests\Admin\Role\StoreRequest::class,
                'update' => \App\Http\Requests\Admin\Role\UpdateRequest::class,
            ]
        );
    }
}
