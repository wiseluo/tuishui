<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;

class AuthERP
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
        // dd($token);
        JWT::decode($token, config('app.user_center_jwt_key'), ['HS256']);
        
        //Auth::setDefaultDriver('erp');
        return $next($request);
    }
}
