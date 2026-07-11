<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\IntroSocialService;

class IntroSocialController extends BaseController
{
    public function __construct(IntroSocialService $service)
    {
        parent::__construct($service);
    }
}
