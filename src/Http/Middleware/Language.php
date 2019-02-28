<?php

namespace ME\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * Middleware to handle permanent locale changings
 */
class Language
{
  public function handle($request, Closure $next)
  {
    if (Session::has('applocale')) {
      App::setLocale(Session::get('applocale'));
    }
    return $next($request);
  }
}