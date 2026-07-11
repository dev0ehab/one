<?php

namespace App\Services\Entity;

use App\Http\Filters\LocationFilter;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LocationService extends BaseService
{
    public function __construct(Location $location, LocationFilter $filter)
    {
        $this->model = $location;
        $this->filter = $filter;
    }

    public function indexApi()
    {
        $data = $this->model::filter($this->filter)
            ->with(['country', 'city'])
            ->latest()
            ->when((!request()->isExtended), function ($query) {
                return $query->paginate(request()->paginate);
            });

        return $this->apiCollectionResponse($data);
    }

    public function showApi($id)
    {
        $data = $this->model::with(['country', 'city'])->findOrFail($id);
        return $this->apiResourceResponse($data);
    }

    public function store($request)
    {
        DB::beginTransaction();

        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
        $data['client_id'] = auth()->user()->id;
        auth()->user()->locations->toQuery()->update(['is_main_location' => 0]);
        $this->model::create($data);

        DB::commit();
        return ['status'  => 'success', 'message' => __('apis.success')];
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $data  = method_exists($request, 'validated') ? $request->validated() : $request->all();
        $model = $this->model::findOrFail($id);

        if (
            $model->is_main_location == 1 &&
            array_key_exists('is_main_location', $data) &&
            $data['is_main_location'] == 0
        ) {
            throw new HttpException(403, __('apis.not_allow_update_main_location'));
        }

        if (
            $model->is_main_location == 0 &&
            array_key_exists('is_main_location', $data) &&
            $data['is_main_location'] == 1
        ) {
            auth()->user()->locations()->update(['is_main_location' => 0]);
        }

        $model->update($data);

        DB::commit();
        return ['status'  => 'success', 'message' => __('apis.updated')];
    }

    public function destroy($id)
    {
        $model = $this->model::findOrFail($id);
        if ($model->is_main_location == 1) {
            throw new HttpException(403, __('apis.not_allow_delete_main_location'));
        }
        $model->delete();
        return ['status'  => 'success', 'message' => __('apis.deleted')];
    }
}
