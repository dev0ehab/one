<?php

namespace App\Http\Middleware\Api;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class OptionalAuthMiddleware
{
    use ResponseTrait ;
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken() && !PersonalAccessToken::findToken($request->bearerToken())) {
            return $this->unauthenticatedReturn();
        }

        if ($request->bearerToken()) {
            Auth::setUser(
                Auth::guard('sanctum')->user()
            );
        }
        return $next($request);
    }
}
