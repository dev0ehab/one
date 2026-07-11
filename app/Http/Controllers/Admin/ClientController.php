<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\ClientService;
use App\Http\Requests\Admin\clients\Store;
use App\Http\Requests\Admin\clients\Update;
use App\Http\Requests\Admin\clients\UpdateBalance;
use App\Http\Requests\Admin\clients\UpdatePoints;

class ClientController extends BaseController
{
    public function __construct(ClientService $service)
    {
        parent::__construct($service, [
            'store'         => Store::class,
            'update'        => Update::class,
            'updateBalance' => UpdateBalance::class,
            'updatePoints'  => UpdatePoints::class,
        ]);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function block()
    {
        return $this->service->block($this->request);
    }

    public function notify()
    {
        return $this->service->notify($this->request);
    }

    public function updateBalance($id)
    {
        return $this->service->updateBalance($this->request, $id);
    }

    public function toggleLanding()
    {
        return $this->service->toggleLanding($this->request);
    }

    public function updatePoints($id)
    {
        return $this->service->updatePoints($this->request, $id);
    }
}
