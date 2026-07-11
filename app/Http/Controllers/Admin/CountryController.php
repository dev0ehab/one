<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Services\Entity\CountryService;

class CountryController extends BaseController
{
    public function __construct(CountryService $service)
    {
        parent::__construct($service, [
            'store' => \App\Http\Requests\Admin\Country\StoreRequest::class,
            'update' => \App\Http\Requests\Admin\Country\UpdateRequest::class,
        ]);
    }
}
