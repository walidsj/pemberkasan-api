<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class AuthController extends Controller
{

    /**
     ** Encode json web token.
     *  https://chalidade.medium.com/authentication-token-for-lumen-with-php-jwt-5686f796f8d5
     * 
     *  @return void
     */
    private function jwt(User $user, $remember_me = 30)
    {
        $payload = [
            'iss' => env('APP_NAME', 'Lumen'),
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // 'agency' => $user->agency->makeHidden(['created_at', 'updated_at']),
            'major' => $user->major->makeHidden(['created_at', 'updated_at']),
            'role' => $user->role,
            'created_at' => Carbon::parse($user->created_at)->timestamp,
            'iat' => time(),
            'exp' => time() + 3600 * 24 * $remember_me
        ];

        return JWT::encode($payload, env('APP_KEY', 'walidganteng'));
    }


    /**
     ** Login an account.
     * 
     * @return void
     */

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (empty($user))
            return response()->json([
                'success' => false,
                'message' => 'Account is not registered.'
            ], 400);

        if (!Hash::check($request->password, $user->password))
            return response()->json([
                'success' => false,
                'message' => 'Your password is wrong.'
            ], 422);

        return response()->json([
            'success' => true,
            'message' => 'Login successfull.',
            'data' => $user->makeHidden(['agency_id', 'major_id']),
            'token' => $this->jwt($user)
        ]);
    }


    /**
     ** Show an account.
     * 
     * @return void
     */

    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Me found.',
            'data' => $request->auth
        ]);
    }


    public function loginVerificator(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->whereIn('role', ['admin', 'verificator'])->first();

        if (empty($user))
            return response()->json([
                'success' => false,
                'message' => 'Account not found.'
            ], 400);

        if (!Hash::check($request->password, $user->password))
            return response()->json([
                'success' => false,
                'message' => 'Your password is wrong.'
            ], 422);

        return response()->json([
            'success' => true,
            'message' => 'Login successfull.',
            'data' => $user->makeHidden(['agency_id', 'major_id']),
            'token' => $this->jwt($user)
        ]);
    }
}
