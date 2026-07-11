<?php

namespace App\Services\Entity;

use App\Jobs\AdminNotify;
use App\Jobs\Notify;
use App\Jobs\SendEmailJob;
use App\Jobs\SendSms;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Client;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationService extends BaseService
{
    public function __construct(Notification $notification)
    {
        $this->model = $notification;
    }

    public function index(): View
    {
        return view('admin.notifications.index');
    }

    public function sendNotifications($request): JsonResponse
    {
        $rows = $this->getRows($request->user_type);

        if ($request->type == 'notify') {
            $this->notify($rows, $request->user_type, $request);
        } elseif ($request->type == 'form_email') {
            $this->sendMail($rows, $request);
        } else {
            $this->sendSms($rows->pluck('phone')->toArray(), $request->message);
        }

        return response()->json();
    }

    protected function notify($rows, $type, $request): void
    {
        if ($type == 'admins') {
            dispatch(new AdminNotify($rows, $request));
        } else {
            dispatch(new Notify($rows, $request));
        }
    }

    protected function sendMail($rows, $request): void
    {
        dispatch(new SendEmailJob($rows->pluck('email'), $request->all()));
    }

    function sendSms($phones, $message)
    {
        dispatch(new SendSms($phones, $message));
    }

    public function getRows($type)
    {
        if ($type == 'admins') {
            return Admin::whereHasRole('admin')->where('id', '!=', auth('admin')->id())->get();
        }

        if ($type == 'clients') {
            return Client::where('is_blocked', false)->get();
        }

        if ($type == 'providers') {
            return Provider::where('is_blocked', false)->get();
        }

        return collect();
    }
}
