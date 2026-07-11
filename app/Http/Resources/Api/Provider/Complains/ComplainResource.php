<?php

namespace App\Http\Resources\Api\Provider\Complains;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplainResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'title'              => $this->title,
            'complain_number'    => $this->complain_number,
            'created_at'         => $this->created_at?->format('Y-m-d H:i:s'),
            'status'             => $this->status,
            'status_translation' => __('apis.' . $this->status),
        ];
    }
}
