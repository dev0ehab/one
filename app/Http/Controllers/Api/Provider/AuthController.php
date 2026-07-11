<?php

namespace App\Http\Controllers\Api\Provider;

use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AuthModelResource;
use App\Http\Requests\Api\Provider\Auth\LoginRequest;
use App\Http\Requests\Api\Provider\Auth\ActivateRequest;
use App\Http\Requests\Api\Provider\Auth\RegisterRequest;
use App\Http\Requests\Api\Provider\Auth\ResendCodeRequest;
use App\Models\Provider;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct()
    {
        $this->service = new AuthenticationService(Provider::class);
    }


    /**
     * Register a new Station.
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('banners')) {
            $data['banners'] = $request->file('banners');
        }

        $data = $this->service->register($data);

        if (($data['key'] ?? '') === 'fail' || empty($data['entity'])) {
            return $this->failMsg($data['msg'] ?? __('auth.failed'));
        }

        return $this->phoneActivationReturn(
            new AuthModelResource($data['entity']),
            $data['msg']
        );
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
     * Activate the Station.
     * @param ActivateRequest $request
     * @return JsonResponse
     */
    public function activate(ActivateRequest $request): JsonResponse
    {
        $data = $this->service->activate($request);
        $provider = AuthModelResource::make($data['entity']);

        if ($data['entity']->is_approved === 0) {
            return $this->response('fail', $data['entity']->rejection_reason ?? __('auth.not_approved'), $provider);
        }

        if ($data['entity']->is_approved !== 1) {
            return $this->AdminApprovalReturn($provider, __('auth.registered_pending_approval'));
        }

        $token = $data['entity']->login();

        return $this->response('success', __('auth.activated'), $provider->setToken($token));
    }


    /**
     * Login the Station.
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->service->login($request);
        $Station = AuthModelResource::make($data['entity']);

        switch ($data['key']) {
            case 'fail':
                $response = $this->failMsg($data['msg']);
                break;

            case 'blocked':
                $response = $this->blockedReturn($Station, $data['msg']);
                break;

            case 'needActive':
                $Station->sendVerificationCode(OTPThrough::PHONE, OTPType::VERIFICATION);
                $response = $this->phoneActivationReturn($Station, $data['msg']);
                break;

            case 'needApproval':
                $response = $this->AdminApprovalReturn($Station, $data['msg']);
                break;

            case 'profileNotCompleted':
                $token = $data['entity']->login();
                $response = $this->profileNotCompletedReturn($Station->setToken($token), $data['msg']);
                break;

            case 'needVerification':
                $response = $this->verificationCodeReturn($Station, $data['msg']);
                break;

            default:
                $token = $data['entity']->login();
                $response = $this->response('success', __('apis.signed'), $Station->setToken($token));
                break;
        }

        return $response;
    }


    /**
     * Logout the Station.
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $data = $this->service->logout();
        return $this->successMsg($data['msg']);
    }


    /**
     * Delete the Station account.
     * @return JsonResponse
     */
    public function deleteAccount(): JsonResponse
    {
        $data = $this->service->deleteAccount();
        return $this->successMsg($data['msg']);
    }
}
