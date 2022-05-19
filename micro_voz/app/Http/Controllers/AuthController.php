<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        return response()->json([
            'token' => $request->user()->createToken('token_user')->plainTextToken,
            'message' => 'Success'
        ]);
    }
    public function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
    public function logout(){
        Auth::user()->tokens()->delete();

        return [
            'message' => ''
        ];
    }

    public function me()
    {
        $user = Auth::user();
        $user->load('type');
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }
}
