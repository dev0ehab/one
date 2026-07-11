<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Http\Filters\Filterable;
use App\Http\Resources\ProviderResource;
use App\Models\Helpers\ProviderHelpers;
use App\Traits\FavoritableTrait;
use App\Traits\ModelTrait;
use App\Traits\RateTotalsTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class Provider extends AuthBaseModel
{
    use Filterable, FavoritableTrait, ModelTrait, SoftDeletes, ProviderHelpers, RateTotalsTrait;

    public const IMAGEPATH = 'providers';

    protected $fillable = [
        'trade_name',
        'owner_name',
        'country_code',
        'password',
        'email',
        'phone',
        'image',
        'national_id',
        'commercial_register_num',
        'city_id',
        'category_id',
        'lat',
        'lng',
        'map_desc',
        'start_time',
        'end_time',
        'banners',
        'bank_name',
        'account_name',
        'account_num',
        'iban',
        'is_active',
        'is_notify',
        'avg_rate',
        'is_blocked',
        'is_approved',
        'has_booking_service',
        'booking_service_expires_at',
        'service_duration_minutes',
        'booking_notes',
        'booking_reminder_minutes',
        'show_in_offers',
        'discount_percentage',
        'cancellation_policy',
        'rejection_reason',
    ];

    protected $casts = [
        'is_active'                  => 'boolean',
        'is_notify'                  => 'boolean',
        'has_booking_service'        => 'boolean',
        'booking_service_expires_at' => 'datetime',
        'service_duration_minutes'   => 'integer',
        'booking_reminder_minutes'   => 'integer',
        'show_in_offers'             => 'boolean',
        'discount_percentage'        => 'decimal:2',
        'lat'                        => 'float',
        'lng'                        => 'float',
        'avg_rate'                   => 'float',
        'banners'                    => 'array',
        'start_time'                 => 'datetime:H:i',
        'end_time'                   => 'datetime:H:i',
    ];

    public function hasActiveBookingService(): bool
    {
        if (!($this->attributes['has_booking_service'] ?? false)) {
            return false;
        }

        if ($this->booking_service_expires_at && $this->booking_service_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getHasBookingServiceAttribute(): bool
    {
        return $this->hasActiveBookingService();
    }

    public function getResource(): ProviderResource
    {
        return new ProviderResource($this);
    }

    /**
     * Backward compatibility: code paths that read $provider->name (wallets, exports, payments).
     */
    public function getNameAttribute(): ?string
    {
        return $this->attributes['trade_name'] ?? null;
    }

    public function getIsOpenAttribute(): bool
    {
        $today = strtolower(now()->locale('en')->dayName);
        return $this->workDays()->where('day', $today)->where('start_at', '<=', now()->format('H:i:s'))->where('end_at', '>=', now()->format('H:i:s'))->exists();
    }

    public function getTodayWorkHoursAttribute(): ?string
    {
        $periods = $this->todayWorkDayPeriods();
        if ($periods->isEmpty()) {
            return null;
        }

        $locale = app()->getLocale();

        return $periods
            ->map(function (WorkDay $wd) use ($locale) {
                return $wd->start_at->locale($locale)->format('g:i A')
                    . ' - '
                    . $wd->end_at->locale($locale)->format('g:i A');
            })
            ->implode(', ');
    }

    /**
     * Today's work slots for the provider (API), 24h start/end per period.
     *
     * @return array<int, array{start: string, end: string}>
     */
    public function getTodayWorkPeriodsAttribute(): array
    {
        return $this->todayWorkDayPeriods()
            ->map(fn (WorkDay $wd) => [
                'start' => $wd->start_at->format('H:i'),
                'end'   => $wd->end_at->format('H:i'),
            ])
            ->values()
            ->all();
    }

    /**
     * Work day rows for the current weekday, ordered by start time.
     */
    protected function todayWorkDayPeriods(): Collection
    {
        $today = strtolower(now()->locale('en')->dayName);

        if ($this->relationLoaded('workDays')) {
            return $this->workDays
                ->where('day', $today)
                ->filter(fn (WorkDay $wd) => $wd->start_at !== null && $wd->end_at !== null)
                ->sortBy(fn (WorkDay $wd) => $wd->start_at->format('H:i:s'))
                ->values();
        }

        return $this->workDays()
            ->where('day', $today)
            ->whereNotNull('start_at')
            ->whereNotNull('end_at')
            ->orderBy('start_at')
            ->get();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function workDays(): MorphMany
    {
        return $this->morphMany(WorkDay::class, 'userable');
    }

    public function room(): MorphOne
    {
        return $this->morphOne(Room::class, 'createable');
    }

    public function chat(): HasOne
    {
        return $this->hasOne(Room::class, 'order_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeWithHasActiveBooking($query)
    {
        $clientId = auth()->id();

        if (!$clientId) {
            return $query;
        }

        return $query->withExists([
            'bookings as has_active_booking' => fn ($q) => $q
                ->where('client_id', $clientId)
                ->whereIn('status', BookingStatusEnum::currentTabStatuses()),
        ]);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ProviderSubscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(ProviderSubscription::class)
            ->where('status', SubscriptionStatusEnum::ACTIVE)
            ->where('ends_at', '>', now())
            ->latestOfMany();
    }

    public function getHasActiveSubscriptionAttribute(): bool
    {
        if ($this->relationLoaded('activeSubscription')) {
            return $this->activeSubscription !== null;
        }

        return $this->activeSubscription()->exists();
    }

    public function ads(): HasMany
    {
        return $this->hasMany(ProviderAd::class);
    }

    /**
     * Public URLs for stored banner filenames.
     *
     * @return array<int, string>
     */
    public function getBannerUrlsAttribute(): array
    {
        return collect($this->banners ?? [])
            ->map(fn (string $filename) => $this->getImage($filename, self::IMAGEPATH))
            ->values()
            ->all();
    }

    /**
     * @param  array<int, UploadedFile|string>  $files
     */
    public function syncBanners(array $files): void
    {
        $stored = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $stored[] = $this->uploadAllTypes($file, self::IMAGEPATH);
            } elseif (is_string($file) && $file !== '') {
                $stored[] = $file;
            }
        }

        foreach ($this->banners ?? [] as $old) {
            if (!in_array($old, $stored, true)) {
                $this->deleteFile($old, self::IMAGEPATH);
            }
        }

        $this->update(['banners' => $stored]);
    }
}
