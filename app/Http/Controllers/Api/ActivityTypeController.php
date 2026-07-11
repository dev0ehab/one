<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ActivityTypesResource;
use App\Services\Entity\ActivityTypeService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ActivityTypeController extends ApiBaseController
{
    use ResponseTrait;

    public function __construct(ActivityTypeService $service)
    {
        parent::__construct($service);
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApi();
        return $this->successData(ActivityTypesResource::collection($data));
    }
}

