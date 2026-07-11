<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Services\Entity\ComplainService;
use App\Http\Requests\Admin\Complain\CompletedRequest;
use Illuminate\Http\JsonResponse;

class ComplainController extends BaseController
{
    public function __construct(ComplainService $service)
    {
        parent::__construct($service);
    }

    public function index($client_id = null)
    {
        return $this->service->index($client_id);
    }

    public function processing($id): JsonResponse
    {
        return $this->service->processing($this->request, $id);
    }

    public function completed($id, CompletedRequest $request): JsonResponse
    {
        return $this->service->completed($request, $id);
    }
}

