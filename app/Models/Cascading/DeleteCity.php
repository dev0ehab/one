<?php

namespace App\Models\Cascading;

class DeleteCity
{
    protected $city;

    public function __construct($city)
    {
        $this->city = $city;
    }

    public function checkClients()
    {
        if ($this->city->locations()->whereHas('client')->exists()) {
            abort(403, __('admin.cannot_delete_city_in_use'));
        }

        return $this;
    }

    public function checkProviders()
    {
        if ($this->city->providers()->exists()) {
            abort(403, __('admin.cannot_delete_city_in_use'));
        }

        return $this;
    }
}
