<?php

namespace App\Services\Entity;

use App\Models\Page;

class PageService extends BaseService
{
    /**
     * Initializes the CategoryService class.
     *
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->model = $page;
    }

    public function indexApi()
    {
        request()->validate([
            "searchArray" => [
                "is_active" => '1',
            ],
        ]);
    }
}
