<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
  use UploadTrait;

  protected $fillable = [
    'room_id',
    'senderable_id',
    'senderable_type',
    'body',
    'type',
    'duration',
    'name',
  ];

  protected $appends = [
    'sender_name',
    'avatar',
  ];

  /**
   * Get the senderable model.
   */
  public function senderable(): MorphTo
  {
    return $this->morphTo();
  }

  public function getSenderNameAttribute(): ?string
  {
    return $this->senderable?->name;
  }

  public function getAvatarAttribute(): ?string
  {
    return $this->senderable?->image;
  }

  public function getBodyAttribute()
  {
    if ($this->type == 'file' || $this->type == 'sound' || $this->type == 'video' || $this->type == 'image') {
      return $this->getImage($this->attributes['body'], 'rooms/' . $this->room_id);
    }
    return $this->attributes['body'];
  }
}
