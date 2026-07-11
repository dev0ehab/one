<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckAuthStatus
{
  use ResponseTrait;

  public function handle(Request $request, Closure $next)
  {
    if (!auth()->check()) {
      return $this->unauthenticatedReturn();
    }

    $user = auth()->user();

    if ($user->is_blocked) {
      return $this->blockedReturn($user, trans('auth.blocked'));
    }

    if (array_key_exists('is_active', $user->getAttributes()) && !$user->is_active) {
      return $this->phoneActivationReturn($user, trans('auth.not_active'));
    }

    // if (array_key_exists('is_approved', $user->getAttributes()) && !$user->is_approved) {
    //   return $this->AdminApprovalReturn($user, trans('auth.not_approve'));
    // }

    if (array_key_exists('is_completed', $user->getAttributes()) && !$user->is_completed) {
      $token = $user->login();
      return $this->profileNotCompletedReturn($user->getResource()->setToken($token), trans('auth.profile_not_completed'));
    }

    return $next($request);
  }
}
