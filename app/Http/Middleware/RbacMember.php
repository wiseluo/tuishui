<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class RbacMember
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
        //dump($request->path());
        if(preg_match('/download/', $request->path()) || preg_match('/^\/$/', $request->path())){//下载|首页
            return $next($request);
        }
        $path = preg_replace('/(\/(\d+,*)*)*$/', '', $request->path());
        $path = preg_replace('/member\//', '', $path);
        if ($request->isMethod('get') && !$request->user('member')->can($path)) {
            abort(403, '权限不足', ['msg'=> json_encode('抱歉，您的权限不足')]);
        }
        return $next($request);
    }
}
