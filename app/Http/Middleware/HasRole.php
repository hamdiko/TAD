<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {

        $roles = is_array($roles) ? $roles : [$roles];

        abort_if(!$request->user() || !$request->user()->hasAnyRole($roles), 403, __("User must have " . implode('or ', $roles) ));

        return $next($request);
    }
}
