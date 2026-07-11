<?php

namespace App\Http\Controllers\Api\Client;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AuthModelResource;
use App\Http\Requests\Api\Client\Auth\LoginRequest;
use App\Http\Requests\Api\Client\Auth\ActivateRequest;
use App\Http\Requests\Api\Client\Auth\RegisterRequest;
use App\Http\Requests\Api\Client\Auth\DeleteAccountRequest;
use App\Http\Requests\Api\Client\Auth\ResendCodeRequest;
use App\Models\Client;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct()
    {
        $this->service = new AuthenticationService(Client::class);
    }


    /**
     * Register a new Client.
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->service->register($request->validated());
        return $this->response($data['key'], $data['msg'], $data['entity'] == [] ? [] : new AuthModelResource($data['entity']));
    }


    /**
     * Resend the activation code.
     * @param ResendCodeRequest $request
     * @return JsonResponse
     */
    public function resendCode(ResendCodeRequest $request): JsonResponse
    {
        $data = $this->service->resendCode($request);
        return $this->response('success', __('auth.code_re_send'), new AuthModelResource($data['entity']));
    }


    /**
     * Activate the Client.
     * @param ActivateRequest $request
     * @return JsonResponse
     */
    public function activate(ActivateRequest $request): JsonResponse
    {
        $data = $this->service->activate($request);
        $token = $data['entity']->login();
        return $this->response('success', __('auth.activated'), AuthModelResource::make($data['entity'])->setToken($token));
    }


    /**
     * Login the Client.
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->service->login($request);
        $Client = AuthModelResource::make($data['entity']);

        switch ($data['key']) {
            case 'fail':
                $response = $this->failMsg($data['msg']);
                break;

            case 'blocked':
                $response = $this->blockedReturn($Client, $data['msg']);
                break;

            case 'needActive':
                $response = $this->phoneActivationReturn($Client, $data['msg']);
                break;

            case 'needApproval':
                $response = $this->AdminApprovalReturn($Client, $data['msg']);
                break;

            case 'needVerification':
                $response = $this->verificationCodeReturn($Client, $data['msg']);
                break;

            default:
                $token = $data['entity']->login();
                $response = $this->response('success', __('apis.signed'), $Client->setToken($token));
                break;
        }

        return $response;
    }


    /**
     * Logout the Client.
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $data = $this->service->logout();
        return $this->successMsg($data['msg']);
    }


    /**
     * Delete the Client account.
     * @return JsonResponse
     */
    public function deleteAccount(DeleteAccountRequest $request): JsonResponse
    {
        $wallet = auth()->user()->wallet;
        $balance = $wallet ? (float) $wallet->balance : 0;

        if ($balance > 0) {
            return $this->failMsg(__('apis.can_not_delete_account_with_balance'));
        }

        $data = $this->service->deleteAccount($request->reason);

        if ($data['key'] === 'fail') {
            return $this->failMsg($data['msg']);
        }

        return $this->successMsg($data['msg']);
    }
}
