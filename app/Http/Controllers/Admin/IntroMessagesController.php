<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\IntroMessageService;

class IntroMessagesController extends BaseController
{
    public function __construct(IntroMessageService $service)
    {
        parent::__construct($service);
    }
}
