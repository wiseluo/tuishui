<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class Rbac
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
        //dd($request->path());
        if(preg_match('/download/', $request->path()) || preg_match('/^\/$/', $request->path())){//下载|首页
            return $next($request);
        }
        $path = preg_replace('/(\/(\d+,*)*)*$/', '', $request->path());
        $path = preg_replace('/admin\//', '', $path);
        //dd($path);
        if ($path != 'admin' && $request->isMethod('get') && !$request->user('admin')->can($path)) {
            return response()->json(['code'=> 403, 'msg'=> '权限不足']);
            //abort(403, '权限不足', ['msg'=> json_encode('抱歉，您的权限不足')]);
        }
        return $next($request);
    }
}
