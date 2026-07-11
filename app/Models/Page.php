<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Spatie\Translatable\HasTranslations;

class Page extends BaseModel
{
    use HasTranslations, ModelTrait;

    const IMAGEPATH = "pages";
    protected $fillable = ['title', 'slug', 'content' , 'image'];
    public $translatable = ['title', 'content'];
}
