<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Client\Complain\StoreComplainRequest;
use App\Services\Entity\ComplainService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ComplainController extends ApiBaseController
{
    use ResponseTrait;

    protected $service;

    public function __construct(ComplainService $service)
    {
        parent::__construct($service, [
            'store' => StoreComplainRequest::class,
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

    public function store(): JsonResponse
    {
        $result = $this->service->store($this->request);

        return $this->response('success', $result['message'] ?? __('apis.success'));
    }

}

