<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class WorkDay extends BaseModel
{
    use ModelTrait, HasTranslations, SoftDeletes , Filterable;

    protected $fillable = [
        'userable_id',
        'userable_type',
        'day',
        'name',
        'start_at',
        'end_at',
    ];

    public array $translatable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
        'start_at' => 'datetime:H:i:s',
        'end_at' => 'datetime:H:i:s',
    ];

    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeForDay($query, string $day)
    {
        return $query->where('day', $day);
    }
}

