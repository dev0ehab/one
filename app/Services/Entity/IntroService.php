<?php

namespace App\Services\Entity;

use App\Models\Intro;

class IntroService extends BaseService
{
    /**
     * Initializes the IntroService class.
     *
     * @param Intro $intro The intro model instance.
     */
    public function __construct(Intro $intro)
    {
        $this->model = $intro;
    }
}
