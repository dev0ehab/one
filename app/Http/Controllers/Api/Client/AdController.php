<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\Ad\AdResource;
use App\Http\Resources\Api\Client\Ad\AdsCollection;
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
        $ads = $this->service->listApprovedAds();
        return $this->successData(new AdsCollection($ads));
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->service->showApprovedAd($id);

        if ($result['status'] === 'fail') {
            return $this->failMsg($result['message']);
        }

        return $this->successData(new AdResource($result['data']));
    }
}
