<?php

namespace App\Services\Auth;

use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Enums\UpdateTypeEnum;
use App\Http\Requests\Api\UpdateBaseRequest;
use App\Models\Contact;
use App\Models\EntityUpdate;
use App\Models\Provider;
use App\Traits\GeneralTrait;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileBaseService
{
    use GeneralTrait, UploadTrait;

    /**
     * Get the user profile
     *
     * @return array
     */
    public function getProfile(): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        $data = $entity;

        // Return the entity data
        return ['key' => 'success', 'msg' => __('apis.success'), 'user' => $data];
    }


    /**
     * Update the user profile
     *
     * @param $request
     * @return array
     */
    public function updateProfile($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Update the user's data
        $entity->update($request);

        // Return the updated user data
        return ['key' => 'success', 'msg' => __('auth.account_updated'), 'user' => $entity->refresh()];
    }


    public function updateProfileRequest($data): array
    {
        $entity = auth()->user();

        if ($entity instanceof Provider) {
            if ($entity->is_approved === 0) {
                $entityData = UpdateBaseRequest::stripProtectedEntityAttributes($data['entity'] ?? []);
                $banners = $entityData['banners'] ?? null;
                unset($entityData['banners']);

                $entityData['is_approved']      = null;
                $entityData['rejection_reason'] = null;
                $entity->update($entityData);

                if (is_array($banners) && $banners !== []) {
                    $entity->syncBanners($banners);
                }
            } else {
                EntityUpdate::create([
                    'updateable_type' => Provider::class,
                    'updateable_id'   => $entity->id,
                    'type'            => UpdateTypeEnum::UPDATE,
                    'data'            => $data,
                ]);
            }
        }


        return ['key' => 'success', 'msg' => __('auth.account_update_requested'), 'user' => $entity->refresh()];
    }



    /**
     * Update the user's password
     *
     * @param $request
     * @return array
     */
    public function updatePassword($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the current password is correct
        if (!Hash::check($request->old_password, $entity->password)) {
            return ['key' => 'fail', 'msg' => __('auth.incorrect_pass'), 'user' => []];
        }

        // Update the user's password
        $entity->update(['password' => $request->password]);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.password_changed'), 'user' => $entity->refresh()];
    }


    /**
     * Send a verification code to the user's phone number
     *
     * @param $request
     * @return array
     */
    public function updatePhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::PHONE, OTPType::UPDATE_PHONE, $request);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.phone_code_sent'), 'user' => $entity->refresh()];
    }


    /**
     * Resend the verification code to the user's phone number
     *
     * @param $request
     * @return array
     */
    public function resendPhoneCode($request): array
    {
        return $this->updatePhone($request);
    }

    /**
     * Verify the user's phone number
     *
     * @param $request
     * @return array
     */
    public function verifyPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::UPDATE_PHONE, OTPThrough::PHONE, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {

            try {
                // Start a database transaction
                DB::beginTransaction();

                // delete the user's otp
                $entity->otps()->where(['type' => OTPType::UPDATE_PHONE, 'code' => $request->code])->delete();

                // Update the user's phone number
                $entity->update(['phone' => $request->phone, 'country_code' => $request->country_code]);

                // Commit the transaction
                DB::commit();

                // Return success message
                return ['key' => 'success', 'msg' => __('auth.phone_updated'), 'user' => $entity->refresh()];
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return ['key' => 'fail', 'msg' => $e->getMessage(), 'user' => []];
            }
        }
    }

    /**
     * Send a verification code to the user's email
     *
     * @param $request
     * @return array
     */
    public function updateEmail($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::EMAIL, OTPType::UPDATE_EMAIL, $request);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.email_code_sent'), 'user' => $entity->refresh()];
    }

    /**
     * Resend the verification code to the user's email
     *
     * @param $request
     * @return array
     */
    public function resendEmailCode($request): array
    {
        return $this->updateEmail($request);
    }


    /**
     * Verify the user's email
     *
     * @param $request
     * @return array
     */
    public function verifyEmail($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::UPDATE_EMAIL, OTPThrough::EMAIL, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {

            try {
                // Start a database transaction
                DB::beginTransaction();

                // delete the user's otp
                $entity->otps()->where(['type' => OTPType::UPDATE_EMAIL, 'code' => $request->code])->delete();

                // Update the user's email
                $entity->update(['email' => $request->email]);

                // Commit the transaction
                DB::commit();

                // Return success message
                return ['key' => 'success', 'msg' => __('auth.email_updated'), 'user' => $entity->refresh()];
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return ['key' => 'fail', 'msg' => $e->getMessage(), 'user' => []];
            }
        }
    }

    /**
     * Update the user's device language
     *
     * @param $request
     * @return array
     */
    public function changeLang($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        $locale = $request->preferred_locale ?? $request->lang ?? defaultLang();

        // Update the user's current device language
        $entity->currentDevice()?->update(['preferred_locale' => $locale]);

        App::setLocale($locale);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.lang_updated'), 'user' => $entity->refresh()];
    }


    /**
     * BEGAIN::Second Scenario for updating the phone number
     */

    /**
     * Send a verification code to the user's old phone number
     * @param $request
     * @return array
     */
    public function sendCodeToOldPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::PHONE, OTPType::PHONE_OWNERSHIP, $request);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.phone_code_sent'), 'user' => $entity->refresh()];
    }


    /**
     * Verify the user's old phone number
     * @param $request
     * @return array
     */
    public function verifyOldPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::PHONE_OWNERSHIP, OTPThrough::PHONE, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {
            // delete the user's otp
            $entity->otps()->where(['type' => OTPType::PHONE_OWNERSHIP, 'code' => $request->code])->delete();

            // Return success message
            return ['key' => 'success', 'msg' => __('auth.phone_ownership'), 'user' => $entity->refresh()];
        }
    }


    /**
     * Send a verification code to the user's new phone number
     * @param $request
     * @return array
     */
    public function sendCodeToNewPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::PHONE, OTPType::UPDATE_PHONE, $request);

        return ['key' => 'success', 'msg' => __('auth.new_phone_code_sent'), 'data' => []];
    }


    /**
     * Verify the user's new phone number
     * @param $request
     * @return array
     */
    public function verifyNewPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::UPDATE_PHONE, OTPThrough::PHONE, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {

            try {
                // Start a database transaction
                DB::beginTransaction();

                // delete the user's otp
                $entity->otps()->where(['type' => OTPType::UPDATE_PHONE, 'code' => $request->code])->delete();

                // Update the user's phone number
                $entity->update(['phone' => $request->phone, 'country_code' => $request->country_code]);

                // Commit the transaction
                DB::commit();

                // Return success message
                return ['key' => 'success', 'msg' => __('auth.phone_updated'), 'user' => $entity->refresh()];
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return ['key' => 'fail', 'msg' => $e->getMessage(), 'user' => []];
            }
        }
    }


    /**
     * END::Second Scenario for updating the phone number
     */


    /**
     * Update language
     * @param $request
     * @return array
     */
    public function updateLanguage($request): array
    {
        $locale = $request->validated()['preferred_locale'] ?? $request->input('lang', defaultLang());

        $request->merge(['lang' => $locale]);

        return $this->changeLang($request);
    }

    /**
     * Update Location
     * @param $request
     * @return array
     */
    public function updateLocation($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();
        $entity->update($request->validated());
        // $entity->locations()->where('is_main_location', true)->update($request->validated());
        return ['key' => 'success', 'msg' => __('apis.updated'), 'user' => $entity->refresh()];
    }



    /**
     * Store the user's contacts
     * @param $request
     * @return array
     */
    public function contact($request): array
    {
        Contact::create($request);

        // Return success message
        return ['key' => 'success', 'msg' => __('apis.messageSended')];
    }

    /**
     * Complete the user profile
     * @param $data
     * @return array
     */
    public function completeProfile($data): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();
        // Update the user's profile
        $entity->update($data);


        // update bank details if exists
        if (isset($data['bank_name']) && isset($data['account_name']) && isset($data['account_num']) && isset($data['iban'])) {
            $entity->bank()->updateOrCreate(['bank_name' => $data['bank_name'], 'account_name' => $data['account_name'], 'account_num' => $data['account_num'], 'iban' => $data['iban']]);
        }

        // Return success message
        return ['key' => 'success', 'msg' => __('apis.updated'), 'user' => $entity->refresh()];
    }
}
