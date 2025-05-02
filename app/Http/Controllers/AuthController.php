<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function __construct(private AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request){
        $data = $request->validated();
        $user = $this->authService->registerUser($data);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request){
        $data = $request->validated();
        $user = $this->authService->loginUser($data);
        
        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user['user'],
            'token' => $user['token'],
        ], 200);
        
        
    }





    public function logout(){
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }
        $user->tokens()->delete();
        return response()->json([
            'message' => 'User logged out successfully',
        ], 200);
    }
}