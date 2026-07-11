<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Resources\Api\AuthModelResource;
use App\Services\Auth\ProfileBaseService;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Profile\UpdatePasswordRequest;
use App\Http\Requests\Api\Provider\Profile\UpdateProfileRequest;
use App\Http\Requests\Api\Provider\Profile\UpdateLangRequest;
use App\Http\Requests\Api\Provider\Profile\UpdatePhoneSendCodeRequest;
use App\Http\Requests\Api\Provider\Profile\VerifyUpdatingPhoneRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct(ProfileBaseService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the station profile
     *
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        $provider = auth()->user()->load(['category', 'city', 'workDays']);
        $provider->setAttribute(
            'has_pending_update_request',
            $provider->entityUpdateRequest()->whereNull('is_approved')->exists()
        );

        return $this->response('success', __('apis.success'), AuthModelResource::make($provider));
    }

    /**
     * Update the station profile
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $data = $this->service->updateProfileRequest($request->data);

        return $this->response('success', $data['msg'], AuthModelResource::make($data['user']));
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        auth()->user()->update(['password' => $request->password]);

        return $this->successMsg(__('apis.updated'));
    }

    public function checkPassword(Request $request): JsonResponse
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return $this->failMsg(trans('auth.incorrect_pass'));
        }

        return $this->successMsg(__('auth.correct_password'));
    }

    public function changeLang(UpdateLangRequest $request): JsonResponse
    {
        $data = $this->service->changeLang($request);

        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }

    public function updateLanguage(UpdateLangRequest $request): JsonResponse
    {
        // Update the company's language
        $this->service->updateLanguage($request);

        // Return a success message
        return $this->successMsg(__('apis.updated'));
    }

    /**
     * BEGAIN::Second Scenario for updating the phone number
     */

    public function sendCodeToOldPhone(UpdatePhoneSendCodeRequest $request): JsonResponse
    {
        $data = $this->service->sendCodeToOldPhone($request);

        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }

    public function verifyOldPhone(VerifyUpdatingPhoneRequest $request): JsonResponse
    {
        $data = $this->service->verifyOldPhone($request);

        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }

    public function sendCodeToNewPhone(UpdatePhoneSendCodeRequest $request): JsonResponse
    {
        $data = $this->service->sendCodeToNewPhone($request);

        return $this->response($data['key'], $data['msg'], $data['data']);
    }

    public function verifyNewPhone(VerifyUpdatingPhoneRequest $request): JsonResponse
    {
        $data = $this->service->verifyNewPhone($request);

        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }
}
