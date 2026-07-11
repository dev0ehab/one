<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\Inquiry\RejectInquiryRequest;
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
        $data = $this->service->indexApiForProvider();

        return $this->response('success', __('apis.success'), $data);
    }

    public function show(int $id): JsonResponse
    {
        $data = $this->service->showApiForProvider($id);

        return $this->response('success', __('apis.success'), $data);
    }

    public function accept(int $id): JsonResponse
    {
        $result = $this->service->providerAccept($id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function reject(RejectInquiryRequest $request, int $id): JsonResponse
    {
        $result = $this->service->providerReject($id, $request->reason);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }
}
