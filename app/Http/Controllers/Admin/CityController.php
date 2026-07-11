<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\City;
use App\Services\Entity\CityService;

class CityController extends BaseController
{
    public function __construct(CityService $service)
    {
        parent::__construct($service, [
            'store' => \App\Http\Requests\Admin\City\StoreRequest::class,
            'update' => \App\Http\Requests\Admin\City\UpdateRequest::class,
        ]);
    }
}
