<?php

namespace App\Http\Controllers\Api\Provider;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Notifications\NotificationsCollection;

class NotificationController extends Controller
{
    use ResponseTrait;

    public function switchNotificationStatus()
    {
        // Get the authenticated station
        $station = auth()->user();

        // Toggle the notification status of the station
        $station->update(['is_notify' => !$station->is_notify]);

        // Return the response with the updated notification status
        return $this->response('success', __('apis.updated'), ['notify' => (bool) $station->refresh()->is_notify]);
    }

    public function getNotifications()
    {
        // Retrieve the notifications for the authenticated station
        $notifications = request()->type == 'unread'
            ? auth()->user()->unreadNotifications()->paginate($this->paginateNum())
            : auth()->user()->notifications()->paginate($this->paginateNum());

        // Mark all unread notifications as read for the authenticated station
        auth()->user()->unreadNotifications->markAsRead();

        // Retrieve the paginated notifications for the authenticated station
        $notifications = new NotificationsCollection($notifications);

        // Return the success response with the notifications data
        return $this->successData(['notifications' => $notifications]);
    }

    public function countUnreadNotifications()
    {
        // Get the authenticated station
        $station = auth()->user();

        // Count the number of unread notifications for the station
        $count = $station->unreadNotifications->count();

        // Return the count of unread notifications
        return $this->successData(['count' => $count]);
    }

    public function deleteNotification($notification_id)
    {
        // Delete the notification for the authenticated station
        auth()->user()->notifications()->where('id', $notification_id)->delete();

        // Return the success message
        return $this->successMsg(__('site.notify_deleted'));
    }

    public function deleteNotifications()
    {
        // Delete all notifications for the authenticated station
        auth()->user()->notifications()->delete();

        // Return success message
        return $this->successMsg(__('site.notifys_deleted'));
    }
}
