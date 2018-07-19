<?php

namespace ME\Http\Middleware;

use Closure;

class ForceHttps
{
    /**
     * Handle the request
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!$request->secure() && env('APP_ENV') === 'production') {
            $request->setTrustedProxies([$request->getClientIp()], true);
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
