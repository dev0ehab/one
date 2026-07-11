<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Room extends Model
{

  //! define type consts here

  protected $fillable = [
    'private',
    'type',
    'order_id',
    'createable_id',
    'createable_type',
    'last_message_id',
  ];

  /**
   * Get all of the members for the Room
   *
   * @return HasMany
   */
  public function members(): HasMany
  {
    return $this->hasMany(RoomMember::class)->with('memberable');
  }

  /**
   * Get all of the admins for the Room
   *
   * @return MorphToMany
   */
  public function admins(): MorphToMany
  {
    return $this->morphedByMany(Admin::class, 'memberable', 'room_members');
  }

  /**
   * Get all of the clients for the Room
   *
   * @return MorphToMany
   */
  public function clients(): MorphToMany
  {
    return $this->morphedByMany(Client::class, 'memberable', 'room_members');
  }

  /**
   * Get all of the providers for the Room
   *
   * @return MorphToMany
   */
  public function providers(): MorphToMany
  {
    return $this->morphedByMany(Provider::class, 'memberable', 'room_members');
  }

  /**
   * Get all of the createable models for the Room
   *
   * @return MorphTo
   */
  public function createable(): MorphTo
  {
    return $this->morphTo();
  }

  /**
   * Get all of the messages for the Room
   *
   * @return HasMany
   */
  public function originalMessages(): HasMany
  {
    return $this->hasMany(Message::class);
  }

  /**
   * Get the last message for the Room
   *
   * @return HasOne
   */
  public function lastOriginalMessage(): HasOne
  {
    return $this->hasOne(Message::class, 'id', 'last_message_id');
  }

  /**
   * Get all of the message notifications for the Room
   *
   * @return HasMany
   */
  public function messages(): HasMany
  {
    return $this->hasMany(MessageNotification::class);
  }

  public function loadLastAuthMessage($userable)
  {
    return $this['last_message'] = $this->messages()
      ->where('message_id', $this->last_message_id)
      ->where('userable_id', $userable->id)
      ->where('userable_type', get_class($userable))
      ->with('originalMessage', 'originalMessage.senderable')
      ->first();
  }
}
