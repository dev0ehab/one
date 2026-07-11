<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\UpdateEmail\ChangeEmailRequest;
use App\Http\Requests\Api\Provider\UpdateEmail\VerifyCodeRequest;
use App\Services\Auth\UpdateEmailService;
use App\Traits\ResponseTrait;

class UpdateEmailController extends Controller
{
    use ResponseTrait;

    public function __construct(protected UpdateEmailService $service)
    {
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
