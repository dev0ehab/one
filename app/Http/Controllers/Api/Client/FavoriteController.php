<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Entity\FavoriteService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Client\Favorites\ToggleFavoriteRequest;
use App\Models\Provider;

class FavoriteController extends ApiBaseController
{
    use ResponseTrait;

    protected $service;

    public function __construct(FavoriteService $service)
    {
        parent::__construct($service, []);
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApi();
        return $this->response('success', __('apis.success'), $data);
    }

    public function toggle(ToggleFavoriteRequest $request): JsonResponse
    {
        $provider = Provider::findOrFail($request->provider_id);
        $result = $this->service->toggle($provider);

        return $this->response('success', $result['message'], [
            'is_favorite' => $result['is_favorite'],
        ]);
    }
}

