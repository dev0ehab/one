<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageNotification extends Model
{

  protected $fillable = [
    'room_id',
    'message_id',
    'userable_id',
    'userable_type',
    'is_seen',
    'is_sender',
    'is_flagged',
  ];

  /**
   * Get the userable model.
   * @return BelongsTo
   */
  public function originalMessage(): BelongsTo
  {
    return $this->belongsTo(Message::class, 'message_id');
  }
}
