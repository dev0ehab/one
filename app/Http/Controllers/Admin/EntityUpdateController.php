<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\AdminEntityUpdateService;

class EntityUpdateController extends BaseController
{
    public function __construct(AdminEntityUpdateService $service)
    {
        parent::__construct($service);
    }

    public function indexProvider()
    {
        return $this->service->indexProvider();
    }

    public function showProvider($id)
    {
        return $this->service->showProvider($id);
    }

    public function approveProvider($id)
    {
        return $this->service->approveProvider($id);
    }

    public function rejectProvider($id)
    {
        return $this->service->rejectProvider($id);
    }
}
