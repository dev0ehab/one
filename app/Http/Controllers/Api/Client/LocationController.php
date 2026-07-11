<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Client\Location\StoreLocationRequest;
use App\Http\Requests\Api\Client\Location\UpdateLocationRequest;
use App\Services\Entity\LocationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class LocationController extends ApiBaseController
{
    use ResponseTrait;

    protected $service;

    public function __construct(LocationService $service)
    {
        parent::__construct($service, [
            'store'  => StoreLocationRequest::class,
            'update' => UpdateLocationRequest::class,
        ]);

        request()->merge([
            'mine' => true,
        ]);
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApi();
        return $this->response('success', __('apis.success'), $data);
    }

    public function show($id): JsonResponse
    {
        $data = $this->service->showApi($id);
        return $this->response('success', __('apis.success'), $data);
    }
}

