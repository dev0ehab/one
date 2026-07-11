<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\Package\SubscribeRequest;
use App\Http\Resources\Api\Provider\Package\PackageResource;
use App\Http\Resources\Api\Provider\Package\SubscriptionResource;
use App\Models\Package;
use App\Models\Provider;
use App\Services\Entity\AdService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    use ResponseTrait;

    public function __construct(protected AdService $service)
    {
    }

    public function index(): JsonResponse
    {
        $packages = $this->service->listPackages();
        return $this->successData(PackageResource::collection($packages));
    }

    public function subscribe(SubscribeRequest $request, int $id): JsonResponse
    {
        $package = Package::active()->find($id);
        if (!$package) {
            return $this->failMsg(__('apis.not_found'));
        }

        /** @var Provider $provider */
        $provider = auth()->user();
        $result = $this->service->subscribe($provider, $package, $request->payment_method);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], new SubscriptionResource($result['data']));
    }

    public function currentSubscription(): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();
        $subscription = $this->service->activeSubscription($provider);

        if (!$subscription) {
            return $this->successData(null);
        }

        return $this->successData(new SubscriptionResource($subscription));
    }
}
