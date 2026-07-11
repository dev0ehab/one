<?php

namespace App\Http\Middleware\Api;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckAuthTypeMiddleware
{
    use ResponseTrait;
    public function handle(Request $request, Closure $next, $type)
    {
        $dataArray = explode("\\", auth()->user()->getMorphClass());

        $currentUserModel = strtolower(end($dataArray));

        if ($type == $currentUserModel) {
            return $next($request);
        }

        return $this->unauthorizedReturn([]);
    }
}
