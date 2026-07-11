<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Inquiry extends BaseModel
{
    use Filterable, ModelTrait, SoftDeletes;

    protected $fillable = [
        'inquiry_number',
        'client_id',
        'category_id',
        'title',
        'description',
        'target_type',
        'status',
        'lat',
        'lng',
        'address',
        'accepted_provider_id',
        'accepted_at',
    ];

    protected $casts = [
        'lat'         => 'float',
        'lng'         => 'float',
        'accepted_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function acceptedProvider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'accepted_provider_id')->withTrashed();
    }

    public function images(): HasMany
    {
        return $this->hasMany(InquiryImage::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(InquiryProvider::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Inquiry $inquiry) {
            if (empty($inquiry->inquiry_number)) {
                $inquiry->inquiry_number = static::generateInquiryNumber();
            }
        });
    }

    protected static function generateInquiryNumber(): string
    {
        do {
            $number = 'INQ-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('inquiry_number', $number)->exists());

        return $number;
    }
}
