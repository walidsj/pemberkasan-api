<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
  /**
   ** Handle an incoming request checking user role.
   *  
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string  $role
   * @return mixed
   */
  public function handle($request, Closure $next, ...$roles)
  {
    foreach ($roles as $role) {
      if ($request->auth->role == $role) {
        return $next($request);
      }
    }

    return response()->json([
      'message' => 'Role unauthorized.'
    ], 400);
  }
}
