<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Resources\Api\AuthModelResource;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ForgetPassword\ForgetPasswordSendCodeRequest;
use App\Http\Requests\Api\Client\ForgetPassword\ResetPasswordCheckCodeRequest;
use App\Http\Requests\Api\Client\ForgetPassword\ForgetPasswordCheckCodeRequest;
use App\Services\Auth\AuthenticationService;
use App\Models\Client;

class ForgetPasswordController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct()
    {
        $this->service = new AuthenticationService(Client::class);
    }

    public function forgetPassword(ForgetPasswordSendCodeRequest $request)
    {
        // Create a new instance of AuthService and call the forgetPasswordSendCode method
        $data = $this->service->forgetPasswordSendCode($request);

        // Check if the key is 'fail'
        if ($data['key'] == 'fail') {
            // Return the failure message
            return $this->failMsg($data['msg']);
        }

        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['entity']));
    }

    public function checkCode(ForgetPasswordCheckCodeRequest $request)
    {
        // check the code
        $data = $this->service->forgetPasswordCheckCode($request);

        // Check if the key is 'fail'
        if ($data['key'] == 'fail') {
            // Return the failure message
            return $this->failMsg($data['msg']);
        }

        // Return the success message
        return $this->successMsg($data['msg']);
    }

    public function resetPassword(ResetPasswordCheckCodeRequest $request)
    {
        // Instantiate the AuthService class and call the resetPassword method with the validated data from the request.
        $data = $this->service->resetPassword($request);

        // Return a success message JSON response.
        return $this->successMsg($data['msg']);
    }
}
