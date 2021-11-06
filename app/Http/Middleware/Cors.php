<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
	/**
	 ** Handle an incoming request.
	 *  https://stackoverflow.com/questions/66558151/get-request-result-to-cors-error-in-lumen-8
	 * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

	// public function handle($request, Closure $next)
	// {
	// 	$response = $next($request);
	// 	$response->header('Access-Control-Allow-Origin', '*');
	// 	return $response;
	// }

	/**
	 ** Handle an incoming request.
	 *	 https://www.initekno.com/cara-mengaktifkan-cors-pada-lumen-8-rest-api/
	 * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

	public function handle($request, Closure $next)
	{
		$headers = [
			'Access-Control-Allow-Origin'      => '*',
			'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
			'Access-Control-Allow-Credentials' => 'true',
			'Access-Control-Max-Age'           => '86400',
			'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
		];

		if ($request->isMethod('OPTIONS')) {
			return response()->json('{"method":"OPTIONS"}', 200, $headers);
		}

		$response = $next($request);
		foreach ($headers as $key => $value) {
			$response->header($key, $value);
		}

		return $response;
	}
}
