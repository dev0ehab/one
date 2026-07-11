<?php

namespace App\Http\Resources\Api\Provider\Complains;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplainsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'complain_number'    => $this->complain_number,
            'title'              => $this->title,
            'description'        => $this->description,
            'status'             => $this->status,
            'status_translation' => __('apis.' . $this->status),
            'reply'              => $this->reply,
            'images'             => $this->images->pluck('image'),
            'created_at'         => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
