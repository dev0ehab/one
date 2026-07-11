<?php

namespace App\Services\Entity;

use App\Models\Admin;
use App\Notifications\BlockUser;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Notification;

class AdminService extends BaseService
{
    use ResponseTrait;

    /**
     * Initializes the AdminService class.
     *
     * @param Admin $admin The Admin model instance.
     */
    public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }



    public function index()
    {
        if (request()->ajax()) {
            $admins = $this->model::search(request()->searchArray)->paginate(30);

            $html = view('admin.admins.table', get_defined_vars())->render();

            return response()->json(['html' => $html]);
        }
        return view('admin.admins.index');
    }


    public function notifications()
    {
        auth('admin')->user()->unreadNotifications->markAsRead();
        return view('admin.admins.notifications');
    }

    public function deleteNotifications($request)
    {
        $requestIds = array_column(json_decode($request->data), 'id');
        auth('admin')->user()->notifications()->whereIn('id', $requestIds)->delete();
        return $this->successMsg();
    }

    public function block($request)
    {
        $admin = Admin::findOrFail($request->id);
        $admin->update(['is_blocked' => !$admin->is_blocked]);
        $admin->tokens()->delete();
        
        Notification::send($admin, new BlockUser);
        return response()->json(['message' => $admin->refresh()->is_blocked == 1 ? __('admin.client_blocked') : __('admin.client_unblocked')]);
    }
}
