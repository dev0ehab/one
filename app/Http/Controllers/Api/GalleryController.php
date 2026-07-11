<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\Galleries\GalleryDetialsResource;
use App\Models\Gallery;
use App\Services\Entity\GalleryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class GalleryController extends ApiBaseController
{
    use ResponseTrait;

    public function __construct(GalleryService $gallery)
    {
        parent::__construct($gallery);
    }


    /**
     * Get the show page of the controller.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        // Get the show page of the Gallery
        $gallery = Gallery::findOrFail($id);
        $data = GalleryDetialsResource::make($gallery);
        // Return a json response
        return $this->successData($data);
    }
}
