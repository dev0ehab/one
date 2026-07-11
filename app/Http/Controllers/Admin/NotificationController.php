<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function __construct(NotificationService $service)
    {
        parent::__construct($service);
    }

    public function sendNotifications(Request $request)
    {
        return $this->service->sendNotifications($request);
    }
}
