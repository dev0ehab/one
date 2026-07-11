<?php

namespace App\Http\Resources\Api\Provider\EntityUpdates;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EntityUpdateDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [];
    }

    /**
     * Get current entity data for comparison
     */
    private function getCurrentEntityData()
    {
        return [];
    }
}
