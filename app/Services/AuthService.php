<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OtpService;

class AuthService{

public function __construct(private User $user, private OtpService $otpService){
    $this->user = $user;
}


public function registerUser($data){
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        'password_confirmation' => $data['password_confirmation'],
    ]);
    return $user;
}
Public function loginUser($data){
    $user = Auth::user();
    if (!$user) {
        throw new \Exception('Invalid credentials', 401);
    }
    $credentials = $data->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $otp = $this->otpService->generateOtp();
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token,
            'otp' => $otp->code,
            'expires_at' => $otp->expires_at,
        ];
    } else {
        throw new \Exception('Invalid credentials', 401);
    }

}


}
