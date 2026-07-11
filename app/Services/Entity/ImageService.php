<?php

namespace App\Services\Entity;

use App\Models\Image;

class ImageService extends BaseService
{
    /**
     * Initializes the ImageService class.
     *
     * @param Image $image The image model instance.
     */
    public function __construct(Image $image)
    {
        $this->model = $image;
    }
}
