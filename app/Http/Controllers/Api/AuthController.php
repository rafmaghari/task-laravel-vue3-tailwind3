<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json(['error' => 'Invalid Credentials'], 422);
        }

        # TODO create .env for token
        $token = auth()->user()->createToken('MyApiToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => auth()->user(),
            'token_type' => 'Bearer'
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password)
        ]);
        auth()->login($user);

        $token = auth()->user()->createToken('MyApiToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => auth()->user(),
            'token_type' => 'Bearer'
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
