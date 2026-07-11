<?php

namespace App\Http\Resources\Site;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        return [
            'image' => $this->image,
            'comment' => $this->getTranslation('comment', $locale),
        ];
    }
}
