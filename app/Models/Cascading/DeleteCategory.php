<?php

namespace App\Models\Cascading;

class DeleteCategory
{
    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function checkProviders()
    {
        if ($this->category->providers()->exists()) {
            abort(403, __('admin.cannot_delete_category_with_providers'));
        }

        return $this;
    }
}
