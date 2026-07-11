<?php

namespace App\Services\Entity;

use App\Models\IntroSlider;

class IntroSliderService extends BaseService
{
    /**
     * Initializes the IntroSliderService class.
     *
     * @param IntroSlider $introSlider The intro model instance.
     */
    public function __construct(IntroSlider $introSlider)
    {
        $this->model = $introSlider;
    }
}
