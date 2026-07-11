<?php

namespace App\Models;

use App\Mail\SendMail;
use App\Jobs\SendNotification;
use App\Notifications\GlobalNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\Client;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Contact extends BaseModel
{
    protected const IMAGEPATH = 'contacts';

    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'name',
        'phone',
        'email',
        'country_code',
        'title',
        'message',
        'image',
        'type',
        'status',
        'reply',
    ];

    protected $with = ['contactable'];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
    ];



    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }



    public function setEmailAttribute($value): void
    {
        // check if the contact has contactable
        $this->attributes['email'] = $this->contactable ? $this->contactable->email : $value;
    }


    public function setPhoneAttribute($value): void
    {
        // check if the contact has contactable
        $this->attributes['phone'] = $this->contactable ? $this->contactable->phone : $value;
    }


    public function setNameAttribute($value): void
    {
        // check if the contact has contactable
        $this->attributes['name'] = $this->contactable ? $this->contactable->name : $value;
    }

    /**
     * Send reply email to the contact
     * @param $reply
     */
    public function sendReplayEmail(string $reply): void
    {
        try {
            if (!empty($this->email)) {
                Mail::to($this->email)->send(new SendMail([
                    'title' => 'Replay to your message',
                    'message' => $reply
                ]));
            }
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Failed to send reply email: ' . $e->getMessage());
        }
    }

    public function getUserRedirectAttribute()
    {
        if ($contactable = $this->contactable) {
            return route('admin.users.show', ['id' => $contactable->parent_id ?? $contactable->id]);
        }
        return false;
    }

    public function getImageAttribute()
    {
        $image = $this->defaultImage(self::IMAGEPATH);

        if (isset($this->attributes['image']) && $this->attributes['image'] && $this->attributes['image'] != $this->defaultImageName()) {
            $image = $this->getImage($this->attributes['image'], self::IMAGEPATH);
        }

        return $image;
    }

    public function setImageAttribute($value): void
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['image']) ? $this->deleteFile($this->attributes['image'], self::IMAGEPATH) : '';
            $this->attributes['image'] = $this->uploadAllTypes($value, self::IMAGEPATH);
        }
    }

    public static function boot(): void
    {
        parent::boot();
        
        static::updated(function ($contact) {
            if ($contact->wasChanged('reply') && !empty($contact->reply) && $contact->contactable_type === Client::class && $contact->contactable_id) {
                $client = $contact->contactable;
                
                if ($client) {
                    SendNotification::dispatch(
                        $client,
                        Client::class,
                        $contact->id,
                        GlobalNotification::class,
                        __('notification.title_contact_replied', [], 'ar'),
                        __('notification.title_contact_replied', [], 'en'),
                        __('notification.body_contact_replied', ['reply' => $contact->reply], 'ar'),
                        __('notification.body_contact_replied', ['reply' => $contact->reply], 'en'),
                        NotificationTypeEnum::ContactReplied
                    );
                }
            }
        });
    }
}
