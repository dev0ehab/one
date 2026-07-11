<?php

namespace App\Services\Entity;

use App\Models\Package;
use App\Traits\ReportTrait;

class PackageService extends BaseService
{
    public function __construct(Package $package)
    {
        parent::__construct($package);
    }

    public function index()
    {
        if (request()->ajax()) {
            $packages = $this->model::withCount('subscriptions')
                ->search(request()->searchArray)
                ->reorder()
                ->orderBy('sort')
                ->orderBy('id')
                ->paginate(30);

            $html = view('admin.packages.table', get_defined_vars())->render();

            return response()->json(['html' => $html]);
        }

        return view('admin.packages.index');
    }

    public function destroy($id): array
    {
        $package = $this->model::withCount('subscriptions')->findOrFail($id);

        if ($package->subscriptions_count > 0) {
            return ['status' => 'error', 'message' => __('admin.cannot_delete_package_has_subscriptions')];
        }

        $package->delete();

        if (!$this->isApiRequest()) {
            ReportTrait::addToLog('delete_' . $this->model->lowerModelName());
        }

        return ['status' => 'success'];
    }

    public function destroyAll($request): array
    {
        $ids = array_map(fn($id) => $id->id, json_decode(request()->data));

        $hasSubscriptions = $this->model::whereIn('id', $ids)
            ->whereHas('subscriptions')
            ->exists();

        if ($hasSubscriptions) {
            return ['status' => 'error', 'message' => __('admin.cannot_delete_package_has_subscriptions')];
        }

        $result = $this->model::whereIn('id', $ids)->each(fn($instance) => $instance->delete());

        if ($result) {
            ReportTrait::addToLog('delete_mutli_' . $this->model->getFolderName());
            return ['status' => 'success'];
        }

        return ['status' => 'fail'];
    }
}
