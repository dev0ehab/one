<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\ImageService;

class ImageController extends BaseController
{
    public function __construct(ImageService $service)
    {
        parent::__construct($service);
    }
}
