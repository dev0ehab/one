<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends BaseModel
{
    use Filterable, ModelTrait;

    protected $fillable = [
        'client_id',
        'favoritable_id',
        'favoritable_type',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function favoritable(): MorphTo
    {
        return $this->morphTo();
    }
}

