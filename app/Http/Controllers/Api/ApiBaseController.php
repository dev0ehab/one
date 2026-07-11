<?php

namespace App\Http\Controllers\Api;

use App\Services\Entity\BaseService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    protected $service;
    protected $resource;
    protected $request;
    protected $user;

    /**
     * BaseController constructor.
     *
     * @param BaseService $service
     * @param array $requests
     */
    public function __construct(BaseService $service, array $requests = [])
    {
        $this->middleware(function ($request, $next) use ($service, $requests) {
            $this->service = $service;
            $this->request = app($requests[request()->route()->getActionMethod()] ?? $service->getRequestPath());
            return $next($request);
        });
    }

    /**
     * Get the index page of the controller.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Get the index page of the service
        $data = $this->service->indexApi();

        // Return a json response
        return $this->successData($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $data = $this->service->store($this->request);
        return $this->successData($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update($id): JsonResponse
    {
        $data = $this->service->update($this->request, $id);
        return $this->successData($data);
    }


    /**
     * Get the show page of the controller.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        // Get the show page of the service
        $data = $this->service->showApi($id);

        // Return a json response
        return $this->successData($data);
    }


    /**
     * Delete the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $hasError = false;

        if (method_exists($this, "canDelete")) {

            $check = $this->canDelete($id);

            if ($hasError = (bool) data_get($check, '0', [])) {
                $canDeleteMessage = data_get($check, '0');
            }

        }

        if ($hasError == false) {
            // Delete the specified resource from storage
            $this->service->destroy($id);

            // Return the deleted resource ID
            return $this->successData($id);
        }

        return $this->failMsg($canDeleteMessage ?? __("apis.delete_not_allowed"));
    }
}
