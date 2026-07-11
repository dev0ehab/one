<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplainImage extends BaseModel
{
    use ModelTrait;

    public const IMAGEPATH = 'complains';

    protected $fillable = [
        'complain_id',
        'image',
    ];

    public function complain(): BelongsTo
    {
        return $this->belongsTo(Complain::class);
    }
}

