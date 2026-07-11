<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends BaseModel
{
    use Filterable, ModelTrait, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'client_id',
        'provider_id',
        'provider_ad_id',
        'status',
        'scheduled_at',
        'service_title',
        'service_description',
        'price',
        'payment_method',
        'paid_at',
        'address',
        'cancel_reason',
        'additional_invoice_amount',
        'additional_invoice_status',
        'additional_invoice_reason',
        'additional_invoice_client_reject_reason',
        'additional_invoice_payment_deadline',
        'additional_invoice_paid_at',
        'additional_invoice_payment_method',
        'additional_invoice_commission',
    ];

    protected $casts = [
        'scheduled_at'              => 'datetime',
        'price'                     => 'float',
        'paid_at'                   => 'datetime',
        'additional_invoice_amount'   => 'float',
        'additional_invoice_commission' => 'float',
        'additional_invoice_payment_deadline' => 'datetime',
        'additional_invoice_paid_at'  => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class)->withTrashed();
    }

    public function providerAd(): BelongsTo
    {
        return $this->belongsTo(ProviderAd::class)->withTrashed();
    }

    public function images(): HasMany
    {
        return $this->hasMany(BookingImage::class);
    }

    public function rate(): HasOne
    {
        return $this->hasOne(Rate::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Booking $booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = static::generateBookingNumber();
            }
        });
    }

    protected static function generateBookingNumber(): string
    {
        do {
            $number = 'BK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('booking_number', $number)->exists());

        return $number;
    }
}
