<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            header("Location: " . locale_route('login'));
            die();
           //todo fix this issue where URL /en is not redirecting to /en/login
            //return redirect(locale_route('login'));

        }
    }
}
