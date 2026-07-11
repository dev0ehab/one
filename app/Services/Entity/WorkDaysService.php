<?php

namespace App\Services\Entity;

use App\Http\Filters\WorkDaysFilter;
use App\Models\WorkDay;
use Illuminate\Support\Facades\DB;

class WorkDaysService extends BaseService
{
    public function __construct(WorkDay $workDay, WorkDaysFilter $filter)
    {
        $this->model = $workDay;
        $this->filter = $filter;
    }

    public function indexApi()
    {
        $data = $this->model::filter($this->filter)
            ->latest()
            ->when((!request()->isExtended), function ($query) {
                return $query->paginate(request()->paginate);
            });

        return $this->apiCollectionResponse($data);
    }

    public function updateWorkDays($request)
    {
        DB::beginTransaction();
        
        $entity = auth()->user();
        $entity->workDays()->delete();
        $entity->workDays()->createMany($request['work_days']);

        DB::commit();
        return [
            'status'  => 'success',
            'message' => __('apis.work_days_updated'),
        ];
    }
}
