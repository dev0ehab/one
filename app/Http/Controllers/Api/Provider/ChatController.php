<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\Chat\StartChatRequest;
use App\Services\Entity\ChatService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ChatService $service)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->service->indexApi();

        return $this->response('success', __('apis.success'), $data);
    }

    public function show(int $id): JsonResponse
    {
        $data = $this->service->showApi($id);

        return $this->response('success', __('apis.success'), $data);
    }

    public function start(StartChatRequest $request): JsonResponse
    {
        $data = $this->service->startApi($request->validated('client_id'));

        return $this->response('success', __('apis.success'), $data);
    }
}
