<?php

namespace App\Http\Controllers\Admin;

use App\Models\IntroFqsCategory;
use App\Services\Entity\IntroFqsService;


class IntroFqsController extends BaseController
{
    public function __construct(IntroFqsService $service)
    {
        parent::__construct($service);
        $categories = IntroFqsCategory::get();
        view()->share('categories', $categories);
    }
}
