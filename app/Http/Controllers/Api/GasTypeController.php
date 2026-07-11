<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GasTypesResource;
use App\Services\Entity\GasTypeService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class GasTypeController extends ApiBaseController
{
    use ResponseTrait;

    public function __construct(GasTypeService $service)
    {
        parent::__construct($service);
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApi();
        return $this->successData(GasTypesResource::collection($data));
    }
}
