<?php

namespace App\Http\Controllers\Admin;


use App\Services\Entity\IntroHowWorkService;

class IntroHowWorkController extends BaseController
{
    public function __construct(IntroHowWorkService $service)
    {
        parent::__construct($service);
    }
}
