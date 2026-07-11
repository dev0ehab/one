<?php

namespace App\Services\Entity;

use App\Http\Requests\Api\UpdateBaseRequest;
use App\Models\EntityUpdate;
use App\Models\Provider;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class AdminEntityUpdateService extends BaseService
{
    use ResponseTrait;

    public function __construct(EntityUpdate $entityUpdate)
    {
        parent::__construct($entityUpdate);
        $this->model = $entityUpdate;
    }

    public function indexProvider()
    {
        if (request()->ajax()) {
            $query = $this->model::where('updateable_type', Provider::class)
                ->whereNull('is_approved')
                ->with(['updateable' => function ($query) {
                    $query->with(['city.country', 'category']);
                }])
                ->latest();

            if (request()->has('searchArray')) {
                $searchArray = request()->searchArray;

                if (isset($searchArray['provider_name']) && !empty($searchArray['provider_name'])) {
                    $query->whereHas('updateable', function ($q) use ($searchArray) {
                        $q->where('trade_name', 'like', '%' . $searchArray['provider_name'] . '%');
                    });
                }

                if (isset($searchArray['provider_phone']) && !empty($searchArray['provider_phone'])) {
                    $query->whereHas('updateable', function ($q) use ($searchArray) {
                        $q->where('phone', 'like', '%' . $searchArray['provider_phone'] . '%');
                    });
                }
            }

            $entity_updates = $query->paginate(30);

            $tableViewPath = view()->exists('admin.entity_updates.provider_table')
                ? 'admin.entity_updates.provider_table'
                : 'admin.entity_updates.table';

            $html = view($tableViewPath, compact('entity_updates'))->render();

            return response()->json(['html' => $html]);
        }

        $indexViewPath = view()->exists('admin.entity_updates.provider_index')
            ? 'admin.entity_updates.provider_index'
            : 'admin.entity_updates.index';

        return view($indexViewPath, ['entity_type' => 'provider']);
    }

    public function showProvider($id)
    {
        $entityUpdate = $this->model::where('updateable_type', Provider::class)
            ->with(['updateable' => function ($query) {
                $query->with(['city.country', 'category', 'workDays']);
            }])
            ->findOrFail($id);

        $currentProvider = $entityUpdate->updateable;
        $allData = $entityUpdate->data ?? [];
        $requestedData = $entityUpdate->data['entity'] ?? [];
        unset($requestedData['class']);

        $viewPath = view()->exists('admin.entity_updates.provider_show')
            ? 'admin.entity_updates.provider_show'
            : 'admin.entity_updates.show';

        return view($viewPath, [
            'entityUpdate' => $entityUpdate,
            'currentProvider' => $currentProvider,
            'requestedData' => $requestedData,
            'allData' => $allData,
        ]);
    }

    public function approveProvider($id)
    {
        DB::beginTransaction();
        try {
            $entityUpdate = $this->model::where('updateable_type', Provider::class)
                ->whereNull('is_approved')
                ->findOrFail($id);

            $this->applyProviderUpdateChanges($entityUpdate);
            $entityUpdate->update(['is_approved' => true]);

            DB::commit();

            \App\Traits\ReportTrait::addToLog('approve_provider_update_request');

            return response()->json([
                'status' => 'success',
                'message' => __('admin.update_request_approved_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => __('admin.error_occurred') . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    public function rejectProvider($id)
    {
        try {
            $entityUpdate = $this->model::where('updateable_type', Provider::class)
                ->whereNull('is_approved')
                ->findOrFail($id);

            $entityUpdate->update(['is_approved' => false]);

            \App\Traits\ReportTrait::addToLog('reject_provider_update_request');

            return response()->json([
                'status' => 'success',
                'message' => __('admin.update_request_rejected_successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.error_occurred') . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    private function applyProviderUpdateChanges(EntityUpdate $entityUpdate)
    {
        $data = $entityUpdate->data;
        $entity = $entityUpdate->updateable;

        if (isset($data['entity'])) {
            $entityData = UpdateBaseRequest::stripProtectedEntityAttributes($data['entity']);
            unset($entityData['class']);

            if (isset($entityData['name']) && ! isset($entityData['trade_name'])) {
                $entityData['trade_name'] = $entityData['name'];
            }
            unset($entityData['name']);

            unset($entityData['clinic_name'], $entityData['social_phone'], $entityData['social_email'], $entityData['whatsapp'], $entityData['license_image'], $entityData['owner_image'], $entityData['address'], $entityData['delete_insurance_companies']);

            $banners = $entityData['banners'] ?? null;
            unset($entityData['banners']);

            $entity->update($entityData);

            if (is_array($banners) && $banners !== []) {
                $entity->syncBanners($banners);
            }
        }
    }
}
