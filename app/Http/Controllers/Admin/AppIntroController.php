<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\AppIntroService;

class AppIntroController extends BaseController
{
    public function __construct(AppIntroService $service)
    {
        parent::__construct($service);
    }

}