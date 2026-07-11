<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingImage extends BaseModel
{
    use ModelTrait;

    public const IMAGEPATH = 'bookings';

    protected $fillable = [
        'booking_id',
        'image',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
