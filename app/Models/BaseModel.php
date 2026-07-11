<?php

namespace App\Models;

use App\Traits\{UploadTrait, SearchTrait, ModelTrait};
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use UploadTrait, ModelTrait, SearchTrait;

    public function getImageAttribute()
    {
        $image = $this->defaultImage(static::IMAGEPATH);

        if ($this->attributes['image'] && $this->attributes['image'] != $this->defaultImageName()) {
            $image = $this->getImage($this->attributes['image'], static::IMAGEPATH);
        }

        return $image;
    }

    public function setImageAttribute($value)
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['image']) ? $this->deleteFile($this->attributes['image'], static::IMAGEPATH) : '';
            $this->attributes['image'] = $this->uploadAllTypes($value, static::IMAGEPATH);
        }
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            if (isset($model->attributes['image'])) {
                $model->deleteFile($model->attributes['image'], static::IMAGEPATH);
            }
        });
    }



    /**
     * Check if the model is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active == 1;
    }



    /**
     * Scope a query to only include active records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereIsActive(true);
    }

}
