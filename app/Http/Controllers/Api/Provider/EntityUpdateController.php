<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Provider\RejectUpdateRequest;
use App\Models\EntityUpdate;
use App\Services\Entity\EntityUpdateService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class EntityUpdateController extends ApiBaseController
{
    use ResponseTrait;

    protected $service;

    public function __construct(EntityUpdateService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all pending update requests for workers in this station
     */
    public function all(Request $request)
    {
        return $this->service->getStationUpdateRequests($request);
    }

    /**
     * Show specific update request details
     */
    public function getDetails(EntityUpdate $entityUpdate)
    {
        return $this->service->showUpdateRequest($entityUpdate);
    }

    /**
     * Approve an update request
     */
    public function approve(EntityUpdate $entityUpdate)
    {
        return $this->service->approveUpdateRequest($entityUpdate);
    }

    /**
     * Reject an update request
     */
    public function reject(EntityUpdate $entityUpdate, RejectUpdateRequest $request)
    {
        return $this->service->rejectUpdateRequest($entityUpdate, $request);
    }

    /**
     * Get statistics for update requests
     */
    public function statistics()
    {
        return $this->service->getStationUpdateStatistics();
    }
}
