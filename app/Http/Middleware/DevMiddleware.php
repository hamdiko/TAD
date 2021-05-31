<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DevMiddleware
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
        abort_if(config('app.env') !== 'local', 404);

        return $next($request);
    }
}
