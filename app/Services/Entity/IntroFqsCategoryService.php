<?php

namespace App\Services\Entity;

use App\Models\IntroFqsCategory;

class IntroFqsCategoryService extends BaseService
{
    /**
     * Initializes the IntroService class.
     *
     * @param IntroFqsCategory $introFqsCategory
     */
    public function __construct(IntroFqsCategory $introFqsCategory)
    {
        $this->model = $introFqsCategory;
    }
}
