<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        abort_if(!$user->hasVerifiedEmail(), 403, Lang::get('auth.email_not_verified'));

        abort_if($user->mustVerifyPhoneNumber() && !$user->hasVerifiedPhoneNumber(), 403, Lang::get('auth.phone_not_verified'));

        return $next($request);
    }
}
