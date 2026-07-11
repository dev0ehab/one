<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\SeoService;

class SeoController extends BaseController
{
    public function __construct(SeoService $service)
    {
        parent::__construct($service);
    }
}
