<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogActivity extends BaseModel
{
  protected $fillable = [
    'subject',
    'url',
    'method',
    'ip',
    'agent',
    'admin_id',
  ];

  /**
   * Get the admin that owns the LogActivity
   *
   * @return BelongsTo
   */
  public function admin(): BelongsTo
  {
    return $this->belongsTo(admin::class, 'admin_id', 'id');
  }
}
