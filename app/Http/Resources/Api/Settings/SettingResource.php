<?php

namespace App\Http\Resources\Api\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'address'     => $this['address'],
            'map_desc'    => $this['map_desc'],
            'lat'         => $this['lat'],
            'lng'         => $this['lng'],
            'phone'       => $this['phone'],
            'email'       => $this['email'],
            'name'        => $this["name_" . lang()],
            'description' => $this["description_" . lang()],
            'logo'        => url($this['logo']),
            'fav_icon'    => url($this['fav_icon']),
        ];
    }
}
