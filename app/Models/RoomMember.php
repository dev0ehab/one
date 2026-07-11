<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RoomMember extends Model
{

  protected $fillable = [
    'room_id',
    'memberable_id',
    'memberable_type',
  ];

  /**
   * Get the memberable model.
   * @return MorphTo
   */
  public function memberable(): MorphTo
  {
    return $this->morphTo();
  }

  /**
   * Get the room model.
   * @return BelongsTo
   */
  public function room(): BelongsTo
  {
    return $this->belongsTo(Room::class);
  }
}
