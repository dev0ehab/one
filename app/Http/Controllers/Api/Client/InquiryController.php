<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Inquiry\StoreInquiryRequest;
use App\Services\Entity\InquiryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    use ResponseTrait;

    public function __construct(protected InquiryService $service)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApiForClient();

        return $this->response('success', __('apis.success'), $data);
    }

    public function show(int $id): JsonResponse
    {
        $data = $this->service->showApiForClient($id);

        return $this->response('success', __('apis.success'), $data);
    }

    public function store(StoreInquiryRequest $request): JsonResponse
    {
        $result = $this->service->store($request);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function cancel(int $id): JsonResponse
    {
        $result = $this->service->cancel($id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }
}
