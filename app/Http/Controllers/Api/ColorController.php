<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ColorsResource;
use App\Services\Entity\ColorService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ColorController extends ApiBaseController
{
    use ResponseTrait;

    public function __construct(ColorService $service)
    {
        parent::__construct($service);
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApi();
        return $this->successData(ColorsResource::collection($data));
    }

    public function show($id): JsonResponse
    {
        $data = $this->service->showApi($id);
        return $this->successData(ColorsResource::make($data));
    }
}

