<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    private $token = '';

    public function setToken($value)
    {
        $this->token = $value;
        return $this;
    }

    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'image'             => $this->image,
            'country_code'      => $this->country_code,
            'phone'             => $this->phone,
            'code'              => $this->code,
            'is_notify'         => (bool) $this->is_notify,
            'is_blocked'        => (bool) $this->is_blocked,
            'is_active'         => (bool) $this->is_active,
            'token'             => request()->bearerToken() ?? $this->token,
        ];
    }
}
