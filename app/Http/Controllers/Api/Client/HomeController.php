<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\Entity\ClientHomeService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ClientHomeService $service)
    {
    }

    /**
     * Client home — banner carousel + dashboard cards (bookings, inquiries, offers).
     */
    public function index(): JsonResponse
    {
        return $this->successData($this->service->dashboard());
    }

    /**
     * Client home — today's bookings ordered by time ("وش جدولك اليوم؟").
     */
    public function schedule(): JsonResponse
    {
        return $this->successData($this->service->schedule());
    }
}
