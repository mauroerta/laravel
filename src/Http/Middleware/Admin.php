<?php

namespace ME\Http\Middleware;

use Auth;
use Closure;

class Admin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) {
    if(Auth::guest()) {
      abort(403);
    }

    if(! Auth::user()->isAdmin()) {
      abort(403);
    }

    return $next($request);
  }
}
