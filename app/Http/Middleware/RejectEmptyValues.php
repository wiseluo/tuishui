<?php

namespace App\Http\Middleware;

use Closure;

class RejectEmptyValues
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
        $params = collect($request)->map(function ($item) {
            if (is_array($item)) {
                return collect($item)->map(function ($item) {
                    $item = $item === null ? '' : $item;
                    
                    return $item;
                })->toArray();
            }
            return $item;
        });

        $request->replace($params->all());
        
        return $next($request);
    }
}
