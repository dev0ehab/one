<?php

namespace App\Http\Resources\Site;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'key' => $this->key,
            'image' => $this->image,
            'title' => $this->title,
            'value' => $this->value,
        ];
    }
}
