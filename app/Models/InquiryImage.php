<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryImage extends BaseModel
{
    use ModelTrait;

    public const IMAGEPATH = 'inquiries';

    protected $fillable = [
        'inquiry_id',
        'image',
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }
}
