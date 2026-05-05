<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Symfony\Component\Routing\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class AuthController extends Controller
{

    public function login(LoginRequest $request): JsonResponse {
        $creds = $request->only(['email', 'password']);

        $token = Auth::guard('admin')->attempt($creds);

        if(!$token) {
            return response()->json([
                'locale' => app()->getLocale(),
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $admin = Auth::guard('admin')->user();

        return response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Admin login sucessfull',
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ],
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(): JsonResponse {
        Auth::guard('admin')->logout();

        return response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Logged out successfully'
        ]);
    }

    public function profile(): JsonResponse {
        $admin = Auth::guard('admin')->user();

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'email_verified_at' => $admin->email_verified_at,
                'created_at' => $admin->created_at,
            ]
        ]);
    }

    public function refresh(): JsonResponse {
        return response()->json([
            'locale' => app()->getLocale(),
            'authorization' => [
                'token' => Auth::guard('admin')->refresh(),
                'type' =>'bearer',
            ],
        ]);
    }
}
