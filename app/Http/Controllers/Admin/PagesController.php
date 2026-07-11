<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\PageService;


class PagesController extends BaseController
{

    public function __construct(PageService $service)
    {
        parent::__construct($service);
    }
}
