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
        // UNTUK API, JANGAN REDIRECT - KEMBALIKAN JSON SAJA
        if ($request->is('api/*')) {
            return null; // ← UNTUK API, RETURN NULL
        }
        
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}