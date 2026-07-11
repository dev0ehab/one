<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryProvider extends BaseModel
{
    use ModelTrait;

    protected $fillable = [
        'inquiry_id',
        'provider_id',
        'status',
        'reject_reason',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class)->withTrashed();
    }
}
