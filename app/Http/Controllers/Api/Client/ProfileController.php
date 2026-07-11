<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Api\Client\Profile\VerifyUpdatingPhoneRequest;
use App\Http\Resources\Api\AuthModelResource;
use App\Services\Auth\ProfileBaseService;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Client\Profile\UpdateProfileRequest;
use App\Http\Requests\Api\Client\Profile\UpdatePasswordRequest;
use App\Http\Requests\Api\Client\Profile\ChangePhoneSendCodeRequest;
use App\Http\Requests\Api\Client\Profile\ChangePhoneCheckCodeRequest;
use App\Http\Requests\Api\Client\Profile\ContactsRequest;
use App\Http\Requests\Api\Client\Profile\UpdateLocationRequest;
use App\Http\Requests\Api\Client\Profile\UpdateLangRequest;
use App\Http\Requests\Api\Client\Profile\UpdatePhoneSendCodeRequest;
use App\Models\AuthOtp;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct(ProfileBaseService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the client profile
     *
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        // Get the client profile
        $data = $this->service->getProfile();

        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }

    /**
     * Update the client profile
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $data = $this->service->updateProfile($request->validated());
        return $this->response('success', $data['msg'], AuthModelResource::make($data['user']));
    }

    /**
     * Update the client's password
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        // Update the client's password in the database
        auth()->user()->update([
            'password' => $request->password,
        ]);

        // Return the success message
        return $this->successMsg(__('apis.updated'));
    }

    /**
     * Check if the provided password matches the client's password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPassword(Request $request): JsonResponse
    {
        // Check if the provided password matches the client's password
        if (!Hash::check($request->password, auth()->user()->password)) {
            // If the password is incorrect, return an error message
            return $this->failMsg(trans('auth.incorrect_pass'));
        }

        // If the password is correct, return a success message
        return $this->successMsg(__('auth.correct_password'));
    }




    /**
     * BEGAIN::Main Scenario for updating the phone number
     */

    /**
     * Send a verification code to the client's old phone number
     *
     * @param ChangePhoneSendCodeRequest $request
     * @return JsonResponse
     */
    public function changePhoneSendCode(ChangePhoneSendCodeRequest $request)
    {
        // Update or create a new client update record
        $update = AuthOtp::updateOrCreate([
            'client_id' => auth()->id(),
            'type' => 'phone',
        ], [
            'country_code' => $request->country_code,
            'phone' => $request->phone,
            'code' => '1'
        ])->refresh();

        // Send the code to the client's phone number
        auth()->user()->sendCodeAtSms($update->code, $update->full_phone);

        // Return a success message
        return $this->successMsg(__('apis.success'));
    }


    /**
     * Resend the verification code to the client's old phone number
     *
     * @param ChangePhoneSendCodeRequest $request
     * @return JsonResponse
     */
    public function changePhoneReSendCode(ChangePhoneSendCodeRequest $request)
    {
        // Update or create a new client update record
        $update = AuthOtp::updateOrCreate([
            'client_id' => auth()->id(),
            'type' => 'phone',
            'country_code' => $request->country_code,
            'phone' => $request->phone,
        ], [
            'code' => '1'
        ])->refresh();

        // Send the code to the client's phone number
        auth()->user()->sendCodeAtSms($update->code, $update->full_phone);

        // Return a success message
        return $this->successMsg(__('apis.success'));
    }

    /**
     * Check if the provided code matches the client's old phone number
     *
     * @param ChangePhoneCheckCodeRequest $request
     * @return JsonResponse
     */
    public function changePhoneCheckCode(ChangePhoneCheckCodeRequest $request)
    {
        // Find the client update record with the provided code
        $update = AuthOtp::where([
            'client_id' => auth()->id(),
            'type' => 'phone',
            'code' => $request->code
        ])->first();

        // If no client update record is found, return an error message
        if (!$update) {
            return $this->failMsg(trans('auth.code_invalid'));
        }

        // Update the client's phone and country code with the values from the client update record
        auth()->user()->update([
            'phone' => $update->phone,
            'country_code' => $update->country_code
        ]);

        // Delete the client update record
        $update->delete();

        // Return a success message
        return $this->successMsg(__('auth.phone_updated'));
    }

    /**
     * END::Main Scenario for updating the phone number
     */




    /**
     * BEGAIN::Second Scenario for updating the phone number
     */

    /**
     * Send a verification code to the client's old phone number
     * @param UpdatePhoneSendCodeRequest $request
     * @return JsonResponse
     */
    public function sendCodeToOldPhone(UpdatePhoneSendCodeRequest $request): JsonResponse
    {
        // Send a verification code to the client's old phone number
        $data = $this->service->sendCodeToOldPhone($request);

        // Return the response
        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }


    /**
     * Verify the client's old phone number
     * @param VerifyUpdatingPhoneRequest $request
     * @return JsonResponse
     */
    public function verifyOldPhone(VerifyUpdatingPhoneRequest $request): JsonResponse
    {
        // Verify the client's old phone number
        $data = $this->service->verifyOldPhone($request);

        // Return the response
        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }


    /**
     * Send a verification code to the client's new phone number
     * @param UpdatePhoneSendCodeRequest $request
     * @return JsonResponse
     */
    public function sendCodeToNewPhone(UpdatePhoneSendCodeRequest $request): JsonResponse
    {
        // Send a verification code to the client's new phone number
        $data = $this->service->sendCodeToNewPhone($request);

        // Return the response
        return $this->response($data['key'], $data['msg'], $data['data']);
    }


    /**
     * Verify the client's new phone number
     * @param VerifyUpdatingPhoneRequest $request
     * @return JsonResponse
     */
    public function verifyNewPhone(VerifyUpdatingPhoneRequest $request): JsonResponse
    {
        // Verify the client's new phone number
        $data = $this->service->verifyNewPhone($request);

        // Return the response
        return $this->response($data['key'], $data['msg'], AuthModelResource::make($data['user']));
    }


    /**
     * END::Second Scenario for updating the phone number
     */



    /**
     * Update language
     * @param UpdateLangRequest $request
     * @return JsonResponse
     */
    public function updateLanguage(UpdateLangRequest $request): JsonResponse
    {
        // Update the client's language
        $this->service->updateLanguage($request);

        // Return a success message
        return $this->successMsg(__('apis.updated'));
    }

    /**
     * Update Location
     * @param UpdateLocationRequest $request
     * @return JsonResponse
     */
    public function updateLocation(UpdateLocationRequest $request): JsonResponse
    {
        // Update the client's Location
        $this->service->updateLocation($request);

        // Return a success message
        return $this->successMsg(__('apis.updated'));
    }


    /**
     * Add a new contact
     * @param ContactsRequest $request
     * @return JsonResponse
     */
    public function contact(ContactsRequest $request): JsonResponse
    {
        // Add a new contact
        $data = $this->service->contact($request->validated());

        // Return the response
        return $this->response($data['key'], $data['msg']);
    }

    public function toggleSafetyMode(): JsonResponse
    {
        auth()->user()->update([
            'is_safety_mode' => !auth()->user()->is_safety_mode
        ]);

        return $this->successData([
            'is_safety_mode' => auth()->user()->is_safety_mode
        ]);
    }
}
