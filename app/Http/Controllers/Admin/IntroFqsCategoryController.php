<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\IntroFqsCategoryService;

class IntroFqsCategoryController extends BaseController
{
    public function __construct(IntroFqsCategoryService $service)
    {
        parent::__construct($service);
    }
}
