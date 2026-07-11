<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\BookingService\SubscribeBookingServiceRequest;
use App\Http\Requests\Api\Provider\BookingService\UpdateBookingServiceRequest;
use App\Http\Resources\Api\Provider\BookingService\BookingServiceResource;
use App\Models\Provider;
use App\Services\Entity\BookingServiceSettingService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class BookingServiceController extends Controller
{
    use ResponseTrait;

    public function __construct(protected BookingServiceSettingService $service)
    {
    }

    public function show(): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();

        return $this->successData(
            new BookingServiceResource($this->service->show($provider))
        );
    }

    public function subscribe(SubscribeBookingServiceRequest $request): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();
        $result = $this->service->subscribe($provider, $request->payment_method);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response(
            'success',
            $result['message'],
            new BookingServiceResource($result['data'])
        );
    }

    public function update(UpdateBookingServiceRequest $request): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();
        $result = $this->service->update($provider, $request->validated());

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response(
            'success',
            $result['message'],
            new BookingServiceResource($result['data'])
        );
    }
}
