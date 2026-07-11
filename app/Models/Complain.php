<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Complain extends BaseModel
{
    use ModelTrait, Filterable;

    protected $fillable = [
        'complainable_id',
        'complainable_type',
        'complain_number',
        'status',
        'title',
        'description',
        'reply',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function complainable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ComplainImage::class);
    }

    public function getFolderName(): string
    {
        return 'complains';
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->complain_number)) {
                $model->complain_number = static::generateComplainNumber();
            }
        });
    }

    protected static function generateComplainNumber(): string
    {
        do {
            $complainNumber = 'CP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
        } while (static::where('complain_number', $complainNumber)->exists());

        return $complainNumber;
    }
}
