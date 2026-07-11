<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\NotificationMessageTrait;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use NotificationMessageTrait, ModelTrait;

    public function getTypeAttribute($value)
    {
        return $this->data['type'];
    }

    public function getTitleAttribute($value)
    {
        return $this->getTitle($this->data['type'], lang());
    }

    public function getBodyAttribute($value)
    {
        return $this->getBody($this->data, lang());
    }

    public function getSenderAttribute($value)
    {
        $def = 'App\Models\\' . $this->data['sender_model'];
        $sender = $def::find($this->data['sender']);
        return [
            'name' => $sender->name,
            'avatar' => $sender->avatar,
        ];
    }
}
