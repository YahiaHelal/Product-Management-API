<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(RegisterRequest $request): JsonResponse {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        $token = Auth::guard('api')->login($user);

        return response()->json([
            'locale' => app()->getLocale(),
            'message' => 'User registered Successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],

            'authorization' => [
                'token' => $token,
                'type' => 'bearer'
            ]
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse {
        $creds = $request->only(['email', 'password']);

        $token = Auth::guard('api')->attempt($creds);

        if(!$token) {
            return response()->json([
                'locale' => app()->getLocale(),
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $user = Auth::guard('api')->user();

        return response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Login sucessfull',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(): JsonResponse {
        Auth::guard('api')->logout();

        return response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Logged out successfully',
        ]);
    }

    public function profile(): JsonResponse {
        $user = Auth::guard('api')->user();

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
            ]
        ]);
    }

    public function refresh(): JsonResponse {
        return response()->json([
            'locale' => app()->getLocale(),
            'authorization' => [
                'token' => Auth::guard('api')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}
