<?php

namespace App\Http\Middleware\Api;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckPermissionMiddleware
{
    use ResponseTrait;
    public function handle(Request $request, Closure $next, $permission)
    {

        if (!auth()->user()->hasPermission($permission)) {
            return $this->unauthorizedReturn([]);
        }

        return $next($request);
    }
}
