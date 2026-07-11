<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\ForgetPassword\ForgetPasswordCheckCodeRequest;
use App\Http\Requests\Api\Provider\ForgetPassword\ForgetPasswordSendCodeRequest;
use App\Http\Requests\Api\Provider\ForgetPassword\ResetPasswordCheckCodeRequest;
use App\Http\Resources\Api\AuthModelResource;
use App\Models\Provider;
use App\Services\Auth\AuthenticationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ForgetPasswordController extends Controller
{
    use ResponseTrait;

    protected AuthenticationService $service;

    public function __construct()
    {
        $this->service = new AuthenticationService(Provider::class);
    }

    public function forgetPassword(ForgetPasswordSendCodeRequest $request): JsonResponse
    {
        $data = $this->service->forgetPasswordSendCode($request);

        if ($data['key'] === 'fail') {
            return $this->failMsg($data['msg']);
        }

        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['entity']));
    }

    public function checkCode(ForgetPasswordCheckCodeRequest $request): JsonResponse
    {
        $data = $this->service->forgetPasswordCheckCode($request);

        if ($data['key'] === 'fail') {
            return $this->failMsg($data['msg']);
        }

        return $this->successMsg($data['msg']);
    }

    public function resetPassword(ResetPasswordCheckCodeRequest $request): JsonResponse
    {
        $data = $this->service->resetPassword($request);

        return $this->successMsg($data['msg']);
    }
}
