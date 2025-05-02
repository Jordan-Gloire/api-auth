<?php

use App\Models\Otp;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Support\Facades\Auth;

class OtpService{

    public function __construct(private Otp $otp,private User $user){
        $this->otp=$otp;
        $this->user=$user;
    }

    public function generateOtp(){
        $user = Auth::user();
        $code = random_int(1000000,999999);
        $otp = Otp::create([
            'code'=> $code,
            'expires_at' => now()->addMinutes(3),
            'is_used' => false,
            'user_id'=> $user->id
        ]);
        $user->notify(new SendOtpNotification($user,$code));
        return $otp;
    }











}