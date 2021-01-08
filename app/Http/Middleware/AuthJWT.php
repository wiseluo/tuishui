<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AuthJWT\JWTService;
use Illuminate\Support\Facades\Auth;

class AuthJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');
        $decoded = JWTService::verification($token);
        $request->user = $decoded;
        
        Auth::setDefaultDriver('api');
        return $next($request);
    }
}
