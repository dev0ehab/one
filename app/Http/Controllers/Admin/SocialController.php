<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\SocialService;

class SocialController extends BaseController
{
    public function __construct(SocialService $service)
    {
        parent::__construct(
            $service,
            [
                'store' => \App\Http\Requests\Admin\Social\StoreRequest::class,
                'update' => \App\Http\Requests\Admin\Social\UpdateRequest::class,
            ]
        );
    }

    public function store()
    {
        return $this->service->store($this->request);
    }

    public function update($id)
    {
        return $this->service->update($this->request, $id);
    }
}
