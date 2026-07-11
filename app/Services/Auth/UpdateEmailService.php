<?php

namespace App\Services\Auth;

use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Http\Resources\Api\AuthModelResource;
use App\Traits\GeneralTrait;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\DB;

class UpdateEmailService
{
    use GeneralTrait, UploadTrait;

    public function changeEmail($request)
    {
        $entity = $this->checkEntity();

        if (is_array($entity)) {
            return $entity;
        }

        $entity->sendVerificationCode(OTPThrough::EMAIL, OTPType::UPDATE_EMAIL, $request);

        //        $code = $this->getOtp($entity)->code;

        //        $entity->sendEmail($code);

        return ['key' => 'success', 'msg' => __('apis.success'), 'data' => AuthModelResource::make($entity)];
    }

    public function verifyEmail($request)
    {
        $entity = $this->checkEntity();

        if (is_array($entity)) {
            return $entity;
        }

        $response = $this->checkOtp($entity, $request);

        if ($response['key'] == 'success') {

            $entity->update(['email' => $request->email]);
            // $this->confirmNewEmail($entity, $request->code);

            return ['key' => 'success', 'msg' => __('apis.success'), 'data' => AuthModelResource::make($entity)];
        }

        return $response;
    }

    protected function checkOtp($entity, $request)
    {
        $response = $entity->checkOtp($request->code, OTPType::UPDATE_EMAIL, OTPThrough::EMAIL, $request);

        if ($response == 'fail') {
            $response = ['key' => 'fail', 'msg' => __('auth.code_invalid')];
        } elseif ($response == 'expired') {
            $response = ['key' => 'fail', 'msg' => __('auth.code_expired')];
        } else {
            $response = ['key' => 'success', 'msg' => __('auth.code_checked'), 'data' => AuthModelResource::make($entity->refresh())];
        }

        return $response;
    }

    protected function confirmNewEmail($entity, $code)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();
            // delete the user's otp
            $otp = $entity
                ->otps()
                ->where(['type' => OTPType::UPDATE_EMAIL, 'code' => $code])
                ->latest()
                ->first();



            // Update the user's password
            $entity->update(['email' => $otp->identifier]);

            $otp->delete();

            // Commit the transaction
            DB::commit();
            // Return success message
            return [
                'key' => 'success',
                'msg' => __('auth.password_changed'),
            ];
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            // Return failure message
            return [
                'key' => 'fail',
                'msg' => $e->getMessage(),
            ];
        }
    }

    protected function checkEntity()
    {
        $entity = auth()->user();

        if (empty($entity)) {
            return ['key' => 'fail', 'msg' => __('auth.incorrect_email'), 'data' => []];
        }

        return $entity;
    }

    protected function getOtp($entity, $code = null)
    {
        return $entity
            ->otps()
            ->when(!empty($code), fn($q) => $q->where('code', $code))
            ->where('type', OTPType::UPDATE_EMAIL)
            ->latest()
            ->first();
    }
}
