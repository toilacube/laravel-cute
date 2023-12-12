<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Tymon\JWTAuth\Contracts\Providers\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
       if (Auth::check() && !Redis::exists(Auth::getToken())) {
        $user = Auth::user();

        if($user->role === 'admin'){
            return $next($request);
        }
        // Check the user's role against the provided role
        if ($user->role === $role) {
            return $next($request);
        }
    }

    // Redirect or return an unauthorized response
    return response('Unauthorized', 401);
    }
}
