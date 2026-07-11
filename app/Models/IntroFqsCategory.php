<?php

namespace App\Models;

use App\Models\Cascading\DeleteIntroFqsCategory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class IntroFqsCategory extends BaseModel
{
    use HasTranslations;

    protected $fillable = ['title'];

    public $translatable = ['title'];

    protected $with = ['questions'];

    public function questions(): HasMany
    {
        return $this->hasMany(IntroFqs::class, 'intro_fqs_category_id', 'id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($model) {
            (new DeleteIntroFqsCategory($model))
                ->checkQuestions();
        });
    }
}

