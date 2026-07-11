<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Contacts\ReplyConract;
use App\Services\Entity\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends BaseController
{
    public function __construct(ContactService $service)
    {
        parent::__construct($service , [
            'reply' => ReplyConract::class
        ]);
    }

    public function reply($id): JsonResponse
    {
        return $this->service->reply($this->request, $id);
    }
}
