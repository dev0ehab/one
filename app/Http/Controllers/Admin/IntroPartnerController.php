<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\IntroPartnerService;

class IntroPartnerController extends BaseController
{
    public function __construct(IntroPartnerService $service)
    {
        parent::__construct($service);
    }
}
