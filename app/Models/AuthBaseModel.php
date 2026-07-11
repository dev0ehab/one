<?php

namespace App\Models;

use App\Models\Helpers\AuthBaseHelpers;
use App\Models\Relationships\AuthBaseRelations;
use App\Traits\ModelTrait;
use App\Traits\SearchTrait;
use App\Traits\SmsTrait;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthBaseModel extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens, SoftDeletes;

    use AuthBaseRelations, UploadTrait, SmsTrait, ModelTrait, SearchTrait, AuthBaseHelpers;

    const IMAGEPATH = 'users';

    protected $hidden = [
        'password',
    ];

    /**
     * Get the user's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest('created_at');
    }

    public function entityUpdateRequest(): MorphMany
    {
        return $this->morphMany(EntityUpdate::class, 'updateable');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        /* creating, created, updating, updated, deleting, deleted, forceDeleted, restored */

        // static::updated(function (AuthBaseModel $model) {
        //     dd($model);
        // });
        static::deleted(function ($model) {
            $model->deleteFile($model->attributes['image'], self::IMAGEPATH);

            // delete all user's devices
            $model->devices()->delete();

            // delete all user's otps
            $model->otps()->delete();

            // delete all user's notifications
            $model->notifications()->delete();

            // delete all user's wallet
            // $model->wallet()->delete();

            $model->update([
                "phone" => $model->id,
                "email" => $model->id . "@email.com",
            ]);
        });

        static::created(function ($model) {
            // $model->wallet()->create();

            if (request()->device_id && request()->device_type) {
                // create a new device for user
                $model->devices()->create([
                    'device_id'        => request()->device_id,
                    'device_type'      => request()->device_type,
                    'mac_address'      => request()->mac_address,
                    'preferred_locale' => request()->header('Accept-Language'),
                    'is_current'       => true,
                ]);
            }
        });
    }
}
