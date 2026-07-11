<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BaseController extends Controller
{
    protected $service;
    protected $resource;
    protected $request;

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
    public function index()
    {
        return $this->service->index();
    }

    /**
     * Get the create page of the controller.
     *
     * @return View
     */
    public function create()
    {
        // Get the create page of the service
        return $this->service->create();
    }

    public function store()
    {
        $data = $this->service->store($this->request);
        return response()->json(['url' => $data['url']]);
    }

    /**
     * Get the edit page of the controller.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        // Get the edit page of the service
        return $this->service->edit($id);
    }


    public function update($id)
    {
        $data = $this->service->update($this->request, $id);
        return response()->json(['url' => $data['url']]);
    }


    /**
     * Get the show page of the controller.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        // Get the show page of the service
        return $this->service->show($id);
    }


    /**
     * Delete the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
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
            return response()->json(['id' => $id]);
        }

        return response()->json( ['message' => $canDeleteMessage ?? __("You are not allowed to delete this resource , because it's associated with other resources.")] , 422);
    }

    /**
     * Delete multiple resources from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyAll(Request $request)
    {
        $ids = array_map(fn($id) => $id->id, json_decode(request()->data));
        
        $hasError = false;

        if (method_exists($this, "canDelete")) {

            $check = $this->canDelete($ids);

            if ($hasError = (bool) data_get($check, '0', [])) {
                $canDeleteMessage = data_get($check, '0');
            }

        }

        if ($hasError == false) {
            // Delete multiple resources from storage
            $this->service->destroyAll($request);

            // Return success response
            return response()->json('success');
        }

        return response()->json( ['message' => $canDeleteMessage ?? __("You are not allowed to delete this resource , because it's associated with other resources.")] , 422);

    }
}
