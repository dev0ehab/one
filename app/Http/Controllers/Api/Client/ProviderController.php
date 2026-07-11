<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\Api\Settings\CategoryResource;
use App\Services\Entity\ProviderService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ProviderController extends ApiBaseController
{
    use ResponseTrait;

    protected $service;

    public function __construct(ProviderService $service)
    {
        parent::__construct($service, []);
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApi();
        return $this->response('success', __('apis.success'), $data);
    }

    public function map(): JsonResponse
    {
        $data = $this->service->mapApi();
        return $this->response('success', __('apis.success'), $data);
    }

    public function show($id): JsonResponse
    {
        $data = $this->service->showApi($id);
        return $this->response('success', __('apis.success'), $data);
    }

    public function reviews($id): JsonResponse
    {
        $data = $this->service->reviewsApi($id);
        return $this->response('success', __('apis.success'), $data);
    }

    public function ads($id): JsonResponse
    {
        $data = $this->service->adsApi($id);
        return $this->response('success', __('apis.success'), $data);
    }

    public function getCategories(): JsonResponse
    {
        $data = $this->service->getCategories();
        return $this->response('success', __('apis.success'), CategoryResource::collection($data));
    }
}

