<?php

namespace App\Http\Middleware\Admin;

use Closure;
use App\Models\Permission;
use App\Traits\ResponseTrait;
use App\Traits\AdminFirstRouteTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminMiddleware
{
    use ResponseTrait, AdminFirstRouteTrait;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is not logged in as an admin or the role ID is not greater than 0
        if (!Auth::guard('admin')->check()) {
            // Store the current URL in session before redirecting to the admin login page
            session()->put('beforeLoginUrl', $request->fullUrl());
            return redirect()->route('admin.login');
        }


        if (auth('admin')->user()->is_blocked) {
            auth('admin')->logout(); // Log out user
            return redirect(route('admin.login'));
        }

        if (auth('admin')->check() && auth('admin')->user()->type == 'super_admin') {
            if (session()->has('beforeLoginUrl')) {
                // Retrieve the stored URL and remove it from session
                $currentUrl = session()->get('beforeLoginUrl');
                session()->remove('beforeLoginUrl');
                return redirect()->to($currentUrl);
            }
            // If the user is a super admin, allow the request to proceed
            return $next($request);
        }

        // Get the permissions for the current admin role
        $permissions = Permission::where('role_id', auth('admin')->user()->role_id)->pluck('permission')->toArray();

        // Check if the current route is not in the list of permissions
        if (!in_array(Route::currentRouteName(), $permissions)) {
            // Handle unauthorized access for AJAX requests
            if ($request->ajax()) {
                // Return an unauthorized response for AJAX requests
                return $this->unauthorizedReturn(['type' => 'notAuth']);
            }

            // Handle unauthorized access for non-AJAX requests
            if (!count($permissions)) {
                // Invalidate session and regenerate token before redirecting to admin login page
                session()->invalidate();
                session()->regenerateToken();
                return redirect(route('admin.login'));
            }

            // Flash danger message and redirect to the appropriate route based on permissions
            session()->flash('danger', trans('auth.not_authorized'));
            return redirect()->route($this->getAdminFirstRouteName($permissions));
        }

        // Handle redirect after successful login
        if (session()->has('beforeLoginUrl')) {
            // Retrieve the stored URL and remove it from session
            $currentUrl = session()->get('beforeLoginUrl');
            session()->remove('beforeLoginUrl');
            return redirect()->to($currentUrl);
        }

        // Proceed with the next request handler
        return $next($request);
    }
}
