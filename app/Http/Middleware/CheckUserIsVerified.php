<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && ! Auth::user()->is_active) {
            $isActive = Auth::user()->is_active;
            Auth::logout();

            return redirect()->back()->withErrors(! $isActive ? 'Your account is not active. Please contact to administrator.' : 'Please verify your email address.');
        }

        return $response;
    }
}
