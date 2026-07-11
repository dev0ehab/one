<?php

namespace App\Models\Cascading;

class DeleteIntroFqsCategory
{
    /**
     * @var \App\Models\IntroFqsCategory
     */
    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function checkQuestions()
    {
        if ($this->category->questions()->exists()) {
            abort(403, __('admin.exceptions.cannot_delete_intro_fqs_category_for_existing_questions'));
        }

        return $this;
    }
}
