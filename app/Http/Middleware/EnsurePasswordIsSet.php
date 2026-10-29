<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordIsSet
{
    /**
     * Redirect users who registered via OTP and have not yet set a real password.
     * They must set their password before accessing any protected pages.
     *
     * Allowed routes while in this forced state:
     *  - user.set-password (GET + POST)
     *  - logout
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->must_set_password) {
            $allowedRoutes = ['user.set-password', 'user.set-password.store', 'logout'];

            if (!in_array($request->route()?->getName(), $allowedRoutes)) {
                return redirect()->route('user.set-password')
                    ->with('info_message', 'Please set your password before continuing.');
            }
        }

        return $next($request);
    }
}
