<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Services\Entity\ProviderCategoryService;
use App\Http\Requests\Admin\ProviderCategory\StoreRequest;
use App\Http\Requests\Admin\ProviderCategory\UpdateRequest;

class ProviderCategoryController extends BaseController
{
    public function __construct(ProviderCategoryService $service)
    {
        parent::__construct($service, [
            'store'  => StoreRequest::class,
            'update' => UpdateRequest::class,
        ]);
    }
}

