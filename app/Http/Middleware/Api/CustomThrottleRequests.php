<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;

class CustomThrottleRequests extends ThrottleRequests
{
    /**
     * Skip throttling for local/dev and Postman collection traffic.
     */
    protected function shouldSkipThrottling(Request $request): bool
    {
        if (app()->environment('local', 'development', 'testing')) {
            return true;
        }

        if ($request->header('X-ONE-Postman') === '1') {
            return true;
        }

        return (bool) env('API_THROTTLE_DISABLED', false);
    }

    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        if ($this->shouldSkipThrottling($request)) {
            return $next($request);
        }

        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }

    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        return is_callable($responseCallback)
            ? new HttpResponseException($responseCallback($request, $headers))
            : new ThrottleRequestsException(__('admin.Too Many Attempts.'), null, $headers);
    }
}
