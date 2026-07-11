<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Services\Entity\RateService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class RateController extends Controller
{
    use ResponseTrait;

    public function __construct(protected RateService $service)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApiForProvider();

        return $this->response('success', __('apis.success'), $data);
    }
}
