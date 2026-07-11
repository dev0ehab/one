<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Provider\Profile\UpdateWorkDaysRequest;
use App\Services\Entity\WorkDaysService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class WorkDaysController extends ApiBaseController
{
    use ResponseTrait;

    public function __construct(WorkDaysService $service)
    {
        parent::__construct($service, [
            'updateWorkDays' => UpdateWorkDaysRequest::class,
        ]);
    }

    public function index(): JsonResponse
    {
        request()->merge(['mine' => true]);

        return parent::index();
    }

    public function updateWorkDays(): JsonResponse
    {
        $data = $this->service->updateWorkDays($this->request->validated());

        return $this->response($data['status'], $data['message']);
    }
}
