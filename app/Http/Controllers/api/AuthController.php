<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create($data);
        $token = auth('api')->login($user);

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function profile()
    {
        return response()->json(auth('api')->user());
    }
}
