<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\PackageService;
use App\Http\Requests\Admin\Package\StoreRequest;
use App\Http\Requests\Admin\Package\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PackageController extends BaseController
{
    public function __construct(PackageService $service)
    {
        parent::__construct($service, [
            'store'  => StoreRequest::class,
            'update' => UpdateRequest::class,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->service->destroy($id);

        if (($result['status'] ?? '') === 'error') {
            return response()->json(['message' => $result['message']], 422);
        }

        return response()->json(['id' => $id]);
    }

    public function destroyAll(Request $request): JsonResponse
    {
        $result = $this->service->destroyAll($request);

        if (($result['status'] ?? '') === 'error') {
            return response()->json(['message' => $result['message']], 422);
        }

        return response()->json('success');
    }
}
