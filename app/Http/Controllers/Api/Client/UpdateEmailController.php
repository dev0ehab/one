<?php

namespace App\Http\Controllers\Api\Client;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\UpdateEmail\ChangeEmailRequest;
use App\Http\Requests\Api\Client\UpdateEmail\VerifyCodeRequest;
use App\Services\Auth\UpdateEmailService;

class UpdateEmailController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct(UpdateEmailService $service)
    {
        $this->service = $service;
    }

    public function changeEmail(ChangeEmailRequest $request)
    {
        return $this->service->changeEmail($request);
    }
    
    public function verifyEmail(VerifyCodeRequest $request)
    {
        return $this->service->verifyEmail($request);
    }
} 