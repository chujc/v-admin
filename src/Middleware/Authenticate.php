<?php

namespace ChuJC\Admin\Middleware;

use ChuJC\Admin\Facades\Admin;
use ChuJC\Admin\Support\Result;
use Closure;

class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Admin::guard()->guest()) {
            return Result::failed('Unauthorized.', 401)->setStatusCode(401);
        }

        return $next($request);
    }
}
