<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$request->user()) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You must be autenticated to access this resource'
            ], 403);
        }

        return $next($request);
    }
}

