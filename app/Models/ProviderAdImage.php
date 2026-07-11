<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

class ProviderAdImage extends BaseModel
{
    use ModelTrait, UploadTrait;

    public const IMAGEPATH = 'provider_ads';

    protected $fillable = ['provider_ad_id', 'image'];

    public function getImageAttribute(): string
    {
        return $this->getImage($this->attributes['image'] ?? '', self::IMAGEPATH);
    }

    public function setImageAttribute($value): void
    {
        if ($value instanceof UploadedFile) {
            if (!empty($this->attributes['image'])) {
                $this->deleteFile($this->attributes['image'], self::IMAGEPATH);
            }
            $this->attributes['image'] = $this->uploadAllTypes($value, self::IMAGEPATH);

            return;
        }

        if (is_string($value) && $value !== '') {
            $this->attributes['image'] = $value;
        }
    }

    public function ad(): BelongsTo
    {
        return $this->belongsTo(ProviderAd::class, 'provider_ad_id');
    }
}
