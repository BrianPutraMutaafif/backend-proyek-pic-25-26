<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Usage in routes: ->middleware('role:admin') or ->middleware('role:admin|seller')
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $allowed = explode('|', $roles);
        if (!in_array($request->user()->role, $allowed)) {
            return response()->json(['message' => 'Unauthenticated'], 403);
        }

        return $next($request);
    }
}
