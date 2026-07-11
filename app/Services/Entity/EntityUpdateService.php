<?php

namespace App\Services\Entity;

use App\Http\Requests\Api\UpdateBaseRequest;
use App\Http\Requests\Api\Provider\RejectUpdateRequest;
use App\Http\Resources\Api\Provider\EntityUpdates\EntityUpdateDetailsResource;
use App\Http\Resources\Api\Provider\EntityUpdates\EntityUpdateResource;
use App\Http\Resources\Api\Provider\EntityUpdates\EntityUpdatesCollection;
use App\Models\EntityUpdate;
use App\Models\Provider;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityUpdateService
{
    use ResponseTrait;

    public function getStationUpdateRequests(Request $request)
    {
        return $this->successData([]);
    }

    /**
     * Show specific update request details
     */
    public function showUpdateRequest(EntityUpdate $entityUpdate)
    {
        return $this->failMsg(
            __('validation.unauthorized_access')
        );
    }

    /**
     * Approve an update request
     */
    public function approveUpdateRequest(EntityUpdate $entityUpdate)
    {
        return $this->failMsg(
            __('validation.unauthorized_access')
        );
    }

    /**
     * Reject an update request
     */
    public function rejectUpdateRequest(EntityUpdate $entityUpdate, RejectUpdateRequest $request)
    {
        return $this->failMsg(
            __('validation.unauthorized_access')
        );
    }

    /**
     * Get statistics for update requests
     */
    public function getStationUpdateStatistics()
    {
        return $this->successData([
            'total_pending' => 0,
            'total_approved' => 0,
            'total_rejected' => 0,
        ]);
    }

    private function belongsToStation(EntityUpdate $entityUpdate, $station): bool
    {
        return false;
    }

    /**
     * Apply the changes from the update request to the actual entity
     */
    private function applyUpdateChanges(EntityUpdate $entityUpdate)
    {
        $data = $entityUpdate->data;
        $entity = $entityUpdate->updateable;

        if (isset($data['entity'])) {
            $entityData = UpdateBaseRequest::stripProtectedEntityAttributes($data['entity']);
            unset($entityData['class']);

            $entity->update($entityData);
        }

        // Handle relations if any
        if (isset($data['relations'])) {
            foreach ($data['relations'] as $relationName => $relationData) {
                if (method_exists($entity, $relationName)) {
                    $relation = $entity->$relationName();
                    
                    if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne) {
                        $relation->updateOrCreate([], $relationData);
                    } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
                        $entity->update([$relation->getForeignKeyName() => $relationData['id'] ?? null]);
                    }
                }
            }
        }
    }
}
