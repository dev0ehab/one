<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\Ad\StoreAdRequest;
use App\Http\Resources\Api\Provider\Ad\AdResource;
use App\Models\Provider;
use App\Services\Entity\AdService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    use ResponseTrait;

    public function __construct(protected AdService $service)
    {
    }

    public function index(): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();
        $ads = $this->service->listMyAds($provider);
        return $this->successData(AdResource::collection($ads));
    }

    public function store(StoreAdRequest $request): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();
        $result = $this->service->createAd($provider, $request->validated());

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->response('success', $result['message'], new AdResource($result['data']));
    }

    public function show(int $id): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();
        $result = $this->service->showAd($provider, $id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->successData(new AdResource($result['data']));
    }

    public function destroy(int $id): JsonResponse
    {
        /** @var Provider $provider */
        $provider = auth()->user();
        $result = $this->service->deleteAd($provider, $id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->successMsg($result['message']);
    }
}
