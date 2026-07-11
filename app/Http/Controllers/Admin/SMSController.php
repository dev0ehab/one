<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\SmsService;
use Illuminate\Http\JsonResponse;


class SMSController extends BaseController
{
    public function __construct(SmsService $service)
    {
        parent::__construct($service);
    }

    public function change()
    {
        return $this->service->change($this->request);
    }

    public function update($id): JsonResponse|array
    {
        return $this->service->update($this->request, $id);
    }
}
