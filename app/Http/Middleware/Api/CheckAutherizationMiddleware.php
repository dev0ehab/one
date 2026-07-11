<?php

namespace App\Http\Middleware\Api;

use App\Traits\ResponseTrait;
use Closure;
use DB;
use Illuminate\Http\Request;

class CheckAutherizationMiddleware
{
    use ResponseTrait;
    public function handle(Request $request, Closure $next, $table, $id, $attribute ,$user_attribute = "id")
    {
        $user_id = DB::table($table)->where('id', $id)->first()?->$attribute;
        
        if ($user_id != auth()->user()->$user_attribute) {
            return $this->unauthorizedReturn([]);
        }

        return $next($request);
    }
}
