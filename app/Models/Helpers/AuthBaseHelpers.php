<?php


namespace App\Models\Helpers;

use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Events\OtpRequested;
use App\Mail\SendMail;
use App\Models\Device;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Mail;

trait AuthBaseHelpers
{
    ////////////////// accessors  //////////////////////


    /**
     * Get the user's full phone number.
     * @return string
     */
    public function getFullPhoneAttribute(): string
    {
        return $this->attributes['country_code'] . $this->attributes['phone'];
    }


    /**
     * Get the user's image.
     * @return string
     */
    public function getImageAttribute(): string
    {
        $image = $this->defaultImage(self::IMAGEPATH);

        if (isset($this->attributes['image']) && $this->attributes['image']) {
            $image = $this->getImage($this->attributes['image'], $this::IMAGEPATH);
        }


        return $image;
    }


    /**
     * Get the user's current language.
     * @return mixed
     */
    public function getPreferredLocaleAttribute(): mixed
    {
        return $this->current_device()->preferred_locale;
    }

    /**
     * Get the user's current authentication otp.
     * @return string
     */
    public function getCodeAttribute(): string
    {
        return $this->otps()->orderBy("updated_at", "desc")->first()?->code ?? '';
    }


    ////////////////// mutators  //////////////////////


    /**
     * Set the user's phone number.
     * @param  string  $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['phone'] = fixPhone($value);
        }
    }


    /**
     * Set the user's country code.
     * @param  string  $value
     * @return void
     */
    public function setCountryCodeAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['country_code'] = fixPhone($value);
        }
    }


    /**
     * Set the user's image.
     * @param  string  $value
     * @return void
     */
    public function setImageAttribute($value)
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['image']) ? $this->deleteFile($this->attributes['image'], $this::IMAGEPATH) : '';
            $this->attributes['image'] = $this->uploadAllTypes($value, $this::IMAGEPATH);
        }
        if(!empty($value) && !is_file($value)){
            $this->attributes['image'] = $value;
        }
    }


    /**
     * Set the user's password.
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }


    ////////////////// helpers  //////////////////////

    /**
     * check if phone is unique.
     * @return boolean
     */
    public function phoneIsExist($phone, $country_code): bool
    {
        // check if phone is exist in users table or merchants table except the current user
        return $this->where('phone', $phone)->where(
            'country_code',
            $country_code
        )->where('id', '!=', $this->id)->exists();
    }

    /**
     * Mark the user as active/verified.
     * @return $this
     */
    public function markAsActive(): static
    {
        // make user active
        $this->update(['is_active' => true]);

        // delete user otp
        $this->otps()->where('type', OTPType::VERIFICATION)->delete();

        return $this;
    }


    /**
     * Send the verification code.
     * @param  string  $identifierType
     * @param  string  $type
     * @return array
     */
    public function sendVerificationCode($identifierType, $type, $request = null): array
    {
        // get identifier according to identifier type
        if ($identifierType == OTPThrough::PHONE) {
            if ($request?->has('phone')) {
                $identifier = $request->phone;
            } else {
                $identifier = $this->phone ?? $this->full_phone;
            }

            $countryCode = $request->country_code ?? $this->country_code;
            $final = $countryCode . $identifier;
        } else {
            $identifier = $request->email ?? $this->email;
            $countryCode = null;
            $final = $identifier;
        }

        $otp = $this->otps()->updateOrCreate([
            'otpable_id' => $this->id,
            'otpable_type' => $this->getMorphClass(),
            'identifier' => $identifier,
            'country_code' => $countryCode,
            'type' => $type,
        ], [
            'code' => $this->activationCode(),
			'expires_at' => now()->addMinutes(1),
			'updated_at' => now(),
        ]);

        // Send otp based on identifier type
        event(new OtpRequested($otp, $identifierType, $final));

        return ['user' => $this];
    }


    /**
     * Check the verification code.
     * @param  string  $code
     * @param  string  $type
     * @return string
     */
    public function checkOtp($code, $type, $identifier_type = OTPThrough::PHONE, $request = null): string
    {
        // get identifier according to identifier type
        if ($identifier_type == OTPThrough::PHONE) {
            $identifier = $request->phone ?? $this->phone;
            $country_code = $request->country_code ?? $this->country_code;
        } else {
            $identifier = $request->email ?? $this->email;
            $country_code = null;
        }

        $otp = $this->otps()->where([
            'code' => $code,
            'type' => $type,
            'identifier' => $identifier,
            'country_code' => $country_code,
        ])->latest()->first();

        if ($otp && !$otp->isExpired()) {
            return 'success';
        } elseif ($otp && $otp->isExpired()) {
            return 'expired';
        } else {
            return 'fail';
        }
    }


    /**
     * generate verification code.
     * @return string
     */
    private function activationCode(): string
    {
        return '123456';
        return (string) random_int(100000, 999999);
    }


    /**
     * Send OTP to user in sms.
     * @param  string  $code
     * @param  string  $phone
     * @return void
     */
    public function sendCodeAtSms($code, $full_phone = null): void
    {
        (new SmsService())->sendSms($full_phone ?? $this->full_phone, __('apis.activeCode', ['code' => $code]));
    }


    /**
     * Send OTP to user in email.
     * @param  string  $code
     * @return void
     */
    public function sendCodeAtEmail($code, $email = null): void
    {
        // get data depends on user's device lang
        $data = [
            'title' => __('apis.activeCodeTitle'),
            'message' => __('apis.activeCode', ['code' => $code]),
        ];

        // send email
        Mail::to($email ?? $this->email)->send(new SendMail($data));
    }



    /**
     * user login.
     * @return string
     */
    public function login(): string
    {
        // logout all devices
        // $this->logoutAll();

        // update user device
        $this->updateDevice();

        // update user lang
        $this->updateLang();

        // // if the authenticated user is user
        // if ($this->isUser()) {
        //     $this->moveToAuthenticated();
        // }

        return $this->createToken(request()->device_type ?? 'web')->plainTextToken;
    }


    /**
     * update user lang.
     * @param mixed $device_id
     * @param mixed $lang
     * @return void
     */
    public function updateLang(): void
    {
        if (request()->lang) {
            $lang = request()->lang;
        } else {
            $lang = request()->header('Accept-Language') ?? defaultLang();
        }

        if (request()->device_id && request()->device_type) {
            Device::firstWhere([
                'device_id' => request()->device_id,
                'device_type' => request()->device_type,
            ])->update(['preferred_locale' => $lang]);
        }
    }


    /**
     * update user device.
     * @return void
     */
    public function updateDevice(): void
    {
        if (request()->device_id && request()->device_type) {
            $this->currentDevice()->update(['is_current' => false]);
            $this->devices()->updateOrCreate([
                'device_id' => request()->device_id,
                'device_type' => request()->device_type,
            ], [
                'mac_address' => request()->mac_address,
                'is_current' => true,
            ]);
        }
    }


    /**
     * move the user's cart to the authenticated user.
     * @return void
     */
    public function moveToAuthenticated(): void
    {
        // get the current device mac address
        $mac_address = $this->currentDevice()->first()->mac_address;

        // logic depends on the user mac address
        //code...
    }



    /**
     * user logout.
     * @return boolean
     */
    public function logout(): bool
    {
        $this->tokens()?->delete();
        return true;
    }


    /**
     * user logout from all devices.
     * @return boolean
     */
    public function logoutAll(): bool
    {
        $this->tokens()->delete();
        return true;
    }
}
