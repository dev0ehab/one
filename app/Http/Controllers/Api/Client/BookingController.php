<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Booking\CancelBookingRequest;
use App\Http\Requests\Api\Client\Booking\PayBookingRequest;
use App\Http\Requests\Api\Client\Booking\RateBookingRequest;
use App\Http\Requests\Api\Client\Booking\RejectAdditionalInvoiceRequest;
use App\Http\Requests\Api\Client\Booking\StoreBookingRequest;
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
        $data = $this->service->indexApiForClient();
        return $this->response('success', __('apis.success'), $data);
    }

    public function show($id): JsonResponse
    {
        $data = $this->service->showApi($id);
        return $this->response('success', __('apis.success'), $data);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $result = $this->service->store($request);

        return $this->response('success', $result['message'], $result['data']);
    }

    public function pay(PayBookingRequest $request, $id): JsonResponse
    {
        $result = $this->service->pay($request, $id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function cancel(CancelBookingRequest $request, $id): JsonResponse
    {
        $result = $this->service->cancel($request, $id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function acceptAdditionalInvoice($id): JsonResponse
    {
        $result = $this->service->acceptAdditionalInvoice($id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function payAdditionalInvoice(PayBookingRequest $request, $id): JsonResponse
    {
        $result = $this->service->payAdditionalInvoice($request, $id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function rejectAdditionalInvoice(RejectAdditionalInvoiceRequest $request, $id): JsonResponse
    {
        $result = $this->service->rejectAdditionalInvoice($request, $id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function rate(RateBookingRequest $request, $id): JsonResponse
    {
        $result = $this->service->rateProvider($request, (int) $id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], $result['data']);
    }

    public function dismissEmptyBanner(): JsonResponse
    {
        $result = $this->service->dismissEmptyBanner();
        return $this->successMsg($result['message']);
    }

    public function availability(int $providerId): JsonResponse
    {
        $days = request()->filled('days') ? (int) request('days') : null;
        $data = $this->service->availabilityForProvider($providerId, $days);

        return $this->response('success', __('apis.success'), $data);
    }
}
