<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\IntroServiceService;

class IntroServiceController extends BaseController
{
    public function __construct(IntroServiceService $service)
    {
        parent::__construct($service);
    }
}
