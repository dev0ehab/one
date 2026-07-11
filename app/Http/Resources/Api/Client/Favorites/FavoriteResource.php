<?php

namespace App\Http\Resources\Api\Client\Favorites;

use App\Http\Resources\Api\Client\Providers\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray($request): array
    {
        $favoritable = $this->favoritable;

        if (!$favoritable) {
            return [
                'id' => $this->id,
                'favoritable_type' => $this->favoritable_type,
                'favoritable_id' => $this->favoritable_id,
                'item' => null,
            ];
        }

        $item = null;
        $type = null;

        if ($this->favoritable_type === Provider::class) {
            $item = ProviderResource::make($favoritable);
            $type = 'provider';
        }

        return [
            'id' => $this->id,
            'type' => $type,
            'item' => $item,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
