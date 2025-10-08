<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class ForceJsonAuth
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (AuthenticationException $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You must be autenticated to access this resource'
            ], 403);
        }
    }
}
