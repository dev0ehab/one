<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\Booking\IssueAdditionalInvoiceRequest;
use App\Http\Requests\Api\Provider\Booking\RejectBookingRequest;
use App\Services\Entity\BookingService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    use ResponseTrait;

    public function __construct(protected BookingService $service)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApiForProvider();
        return $this->response('success', __('apis.success'), $data);
    }

    public function accept($id): JsonResponse
    {
        $result = $this->service->providerAccept($id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function reject(RejectBookingRequest $request, $id): JsonResponse
    {
        $result = $this->service->providerReject($id, $request->reason);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function issueAdditionalInvoice(IssueAdditionalInvoiceRequest $request, $id): JsonResponse
    {
        $result = $this->service->providerIssueAdditionalInvoice($id, (float) $request->amount, $request->reason);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function complete($id): JsonResponse
    {
        $result = $this->service->providerComplete($id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }
}
