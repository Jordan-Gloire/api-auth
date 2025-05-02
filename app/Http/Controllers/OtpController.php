<?php
namespace App\Http\Controllers;

use OtpService;

class OtpController extends Controller
{
    //
    public function __construct(private OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
}