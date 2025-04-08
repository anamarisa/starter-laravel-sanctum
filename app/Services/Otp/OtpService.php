<?php

namespace App\Services\Otp;

use LaravelEasyRepository\BaseService;

interface OtpService extends BaseService
{
    public function generateAndSendOtp($userId, $phoneNumber);
    public function verifyOtp($data);
    public function resendOtp();
    public function verifyPhoneNumber($data, $id);
}