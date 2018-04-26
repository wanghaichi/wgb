<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['spec_id', 'password', 'phoneNum', 'seatNum']);
        if(! isset($credentials['spec_id']))
            return response()->json(['error' => '场次缺失'], 401);
        if(! isset($credentials['password']))
            return response()->json(['error' => '密码缺失'], 401);
        if(! isset($credentials['phoneNum']))
            return response()->json(['error' => '手机号确实'], 401);
        if(! isset($credentials['seatNum']))
            return response()->json(['error' => '座位缺失'], 401);

        $user = User::where('spec_id', $credentials['spec_id'])->first();
        if(!$user)
            return response()->json(['error' => '场次不存在'], 401);
        if($user->phoneNum != $credentials['phoneNum'])
            return response()->json(['error' => '手机号码错误'], 401);
        if($user->seatNum != $credentials['seatNum'])
            return response()->json(['error' => '座位号错误'], 401);
        
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}