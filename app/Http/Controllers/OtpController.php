<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ResponseHelpers;
use App\Services\Otp\OtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    private $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'otp_number' => 'required|integer',
            ]);

            return $this->otpService->verifyOtp($validated);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function resendOtp()
    {
        try {
            return $this->otpService->resendOtp();
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function reverifyOtp(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'otp_number' => 'required|integer',
            ]);

            return $this->otpService->verifyPhoneNumber($validatedData, $id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }
}
