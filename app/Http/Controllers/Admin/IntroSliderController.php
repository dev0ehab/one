<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ReportTrait;
use App\Services\Entity\IntroSliderService;

class IntroSliderController extends BaseController
{
    use ReportTrait;

    public function __construct(IntroSliderService $service)
    {
        parent::__construct($service);
    }
}
