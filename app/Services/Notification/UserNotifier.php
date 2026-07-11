<?php

namespace App\Services\Notification;

use App\Jobs\SendNotification;
use App\Models\Client;
use App\Models\Provider;
use App\Notifications\GlobalNotification;
use Illuminate\Database\Eloquent\Model;

class UserNotifier
{
    /**
     * Queue in-app + Firebase notification for client or provider users.
     *
     * @param  Client|Provider|iterable<Client|Provider>  $notifiables
     */
    public static function send(
        Client|Provider|iterable $notifiables,
        string $type,
        Model|string $model,
        int|string $modelId,
        array $replace = []
    ): void {
        $targets = $notifiables instanceof Model
            ? collect([$notifiables])
            : collect($notifiables);

        $targets = $targets->filter(fn ($user) => $user && $user->is_notify);

        if ($targets->isEmpty()) {
            return;
        }

        $modelClass = is_string($model) ? $model : $model::class;

        SendNotification::dispatch(
            $targets,
            $modelClass,
            $modelId,
            GlobalNotification::class,
            __('notification.title_' . $type, $replace, 'ar'),
            __('notification.title_' . $type, $replace, 'en'),
            __('notification.body_' . $type, $replace, 'ar'),
            __('notification.body_' . $type, $replace, 'en'),
            $type
        );
    }
}
