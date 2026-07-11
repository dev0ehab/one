<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Country;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Settings\CityResource;
use App\Http\Resources\Api\Settings\CountryResource;
use App\Http\Resources\Api\Settings\CountryWithCitiesResource;

class CountriesAndCitiesController extends Controller
{
    use ResponseTrait;

    public function countries()
    {
        $countries = CountryResource::collection(Country::latest()->get());
        return $this->successData($countries);
    }

    public function cities()
    {
        $cities = City::query()
            ->when(request('country_id'), function ($query) {
                $query->where('country_id', request('country_id'));
            })
            ->get();

        $cities = CityResource::collection($cities);
        return $this->successData($cities);
    }

    public function CountryCities($country_id)
    {
        $cities = CityResource::collection(City::with('country')->where('country_id', $country_id)->latest()->get());
        return $this->successData($cities);
    }

    public function countriesWithCities()
    {
        $countries = CountryWithCitiesResource::collection(Country::with('cities')->latest()->get());
        return $this->successData($countries);
    }
}
