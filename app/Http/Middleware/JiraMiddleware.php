<?php

namespace App\Http\Middleware;

use App\Helper\JiraHelper;
use Closure;
use Illuminate\Http\Request;

class JiraMiddleware
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
        if(!JiraHelper::isEnabled()) {
            return abort(401);
        }
        return $next($request);
    }
}
