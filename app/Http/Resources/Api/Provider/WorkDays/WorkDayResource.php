<?php

namespace App\Http\Resources\Api\Provider\WorkDays;

use App\Enums\WorkDayEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkDayResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        $localizedName = $this->getTranslation('name', $locale);
        if ($localizedName === '' || $localizedName === null) {
            $localizedName = WorkDayEnum::defaultNames($this->day)[$locale]
                ?? WorkDayEnum::defaultNames($this->day)['ar']
                ?? $this->day;
        }

        return [
            'id'       => $this->id,
            'day'      => $this->day,
            'name'     => $localizedName,
            'start_at' => $this->start_at?->format('H:i'),
            'end_at'   => $this->end_at?->format('H:i'),
        ];
    }
}
