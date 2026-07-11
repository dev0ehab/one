<?php

namespace App\Models\Cascading;

class DeleteCountry
{
    /**
     * @var \App\Models\Country
     */
    protected $country;

    public function __construct($country)
    {
        $this->country = $country;
    }

    public function checkCities()
    {
        if ($this->country->cities()->exists()) {
            abort(403, __('admin.cannot_delete_country_in_use'));
        }

        return $this;
    }
}

