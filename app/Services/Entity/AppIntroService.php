<?php

namespace App\Services\Entity;

use App\Models\AppIntro;

class AppIntroService extends BaseService
{
    /**
     * Initializes the AppIntroService class.
     *
     * @param AppIntro $appIntro The app intro model instance.
     */
    public function __construct(AppIntro $appIntro)
    {
        $this->model = $appIntro;
    }
}
