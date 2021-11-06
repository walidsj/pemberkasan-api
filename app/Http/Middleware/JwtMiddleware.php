<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    /**
     ** Handle an incoming request with JWT required.
     *  https://chalidade.medium.com/authentication-token-for-lumen-with-php-jwt-5686f796f8d5
     * 
     *  @param  \Illuminate\Http\Request  $request
     *  @param  \Closure  $next
     *  @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();

        if (!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'success' => false,
                'message' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('APP_KEY', 'walidganteng'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error while decoding token.'
            ], 400);
        }

        $user = User::findOrFail($credentials->sub);

        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        return $next($request);
    }
}
