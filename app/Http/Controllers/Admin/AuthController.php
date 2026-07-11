<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\auth\loginRequest;
use App\Models\Admin;
use App\Models\SiteSetting;
use App\Services\SettingService;
use App\Traits\AdminFirstRouteTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    use AdminFirstRouteTrait;

    /**
     * Set the language for the admin panel
     *
     * @param string $lang
     * @return RedirectResponse
     */
    public function SetLanguage($lang): RedirectResponse
    {
        if (session()->has('lang')) {
            session()->forget('lang');
        }

        $new = in_array($lang, ['ar', 'en']) ? $lang : 'ar';
        session()->put('lang', $new);

        return back();
    }

    public function showLoginForm()
    {
        $data = SettingService::appInformations(SiteSetting::pluck('value', 'key'));
        return view('admin.auth.login', compact('data'));
    }

    public function login(loginRequest $request)
    {
        if ($this->checkTooManyFailedAttempts()) {
            return $this->checkTooManyFailedAttempts();
        }

        $user = Admin::firstWhere('email', $request->email);

        if ($user->is_blocked) {
            return response()->json(['status' => 0, 'message' => __('auth.blocked')]);
        }

        $isAuth = auth()->guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_blocked' => 0
        ], $request->boolean('remember'));

        if ($isAuth) {
            RateLimiter::clear($this->throttleKey());

            session()->put('lang', 'ar');
            return response()->json(['status' => 'login', 'url' => route($this->getAdminFirstRouteName()), 'message' => __('admin.login_successfully_logged')]);
        } else {
            if ($isAuth) {
                auth('admin')->logout();
                return response()->json(['status' => 0, 'message' => __('auth.blocked')]);
            }

            RateLimiter::hit($this->throttleKey(), $seconds = 3600);
            return response()->json(['status' => 0, 'message' => __('admin.wrong credentials')]);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function checkTooManyFailedAttempts()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 10)) {
            return;
        }
        return response()->json(['status' => 0, 'message' => 'IP address banned. Too many login attempts, try after 60 minute']);
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        auth('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect(route('admin.login'));
    }
}
