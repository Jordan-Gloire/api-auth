<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;

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
    public function loginUser($data)
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
        

        // Recherche de l'utilisateur par email
        $user = User::where('email', $credentials['email'])->first();

        // Vérification de l'utilisateur et du mot de passe
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Identifiants invalides', 401);
        }

        $user = Auth::user();

        // Génération du code OTP
        $otp = $this->otpService->generateOtp(); // passe l'utilisateur si nécessaire

        // Création du token d'accès
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }



}
