<?php

namespace App\Models;

use App\Traits\ModelTrait;

class NewsLetters extends BaseModel
{
    use ModelTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news_letters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
    ];
}
