<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['code'=> 401, 'msg'=> '授权失效，请重新登录。']);
                //return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }
        Auth::setDefaultDriver($guard); //设置已认证 guard 为默认 guard
        return $next($request);
    }
}
