<?php

namespace App\Services\Entity;

use App\Models\IntroService;

class IntroServiceService extends BaseService
{
    /**
     * Initializes the IntroServiceService class.
     *
     * @param IntroService $introService The intro model instance.
     */
    public function __construct(IntroService $introService)
    {
        $this->model = $introService;
    }
}
