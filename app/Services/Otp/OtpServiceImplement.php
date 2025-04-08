<?php

namespace App\Services\Otp;

use App\Http\Helpers\ResponseHelpers;
use App\Repositories\DetailUser\DetailUserRepository;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Otp\OtpRepository;
use App\Repositories\User\UserRepository;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OtpServiceImplement extends ServiceApi implements OtpService
{
  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected OtpRepository $mainRepository;
  protected FonnteService $fonnteService;
  protected DetailUserRepository $detailUserRepository;
  protected UserRepository $userRepository;

  public function __construct(OtpRepository $mainRepository, FonnteService $fonnteService, DetailUserRepository $detailUserRepository, UserRepository $userRepository)
  {
    $this->mainRepository = $mainRepository;
    $this->fonnteService = $fonnteService;
    $this->detailUserRepository = $detailUserRepository;
    $this->userRepository = $userRepository;
  }

  public function generateAndSendOtp($userId, $phoneNumber)
  {
    DB::beginTransaction();
    try {
      $otp = rand(1000, 9999);
      $message = "Your OTP for registration is: {$otp}";

      // Send OTP via Fonnte
      $response = $this->fonnteService->requestOtp($phoneNumber, $message);

      if (isset($response['error'])) {
        DB::rollBack();
        return ResponseHelpers::sendError('Failed to send OTP', [$response['error']], 500);
      }

      $otp = $this->mainRepository->create([
        'user_id' => $userId,
        'otp_number' => $otp,
        'expire_time' => now()->addMinutes(5),
      ]);

      DB::commit();

      return ResponseHelpers::sendSuccess('OTP sent successfully', [
        'user_id' => $otp->user_id,
        'otp_number' => $otp->otp_number,
        'expire_time' => $otp->expire_time,
      ], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }

  public function verifyOtp($data)
  {
    try {
      $userId = Auth::id();
      $existingOtp = $this->mainRepository->findByLatest($userId);
      if (!$existingOtp) {
        return ResponseHelpers::sendError('OTP not found', [], 404);
      }

      if (now()->greaterThan($existingOtp->expire_time)) {
        DB::transaction(function () use ($existingOtp) {
          $existingOtp->delete();
        });

        return ResponseHelpers::sendError('OTP expired', [], 400);
      }

      if ($data['otp_number'] != $existingOtp->otp_number) {
        return ResponseHelpers::sendError('Invalid OTP', [], 400);
      }

      $this->userRepository->verifyUpdate($userId);

      return ResponseHelpers::sendSuccess('OTP verified successfully', [
        'user_id' => $existingOtp->user_id,
        'otp_number' => $existingOtp->otp_number,
        'expire_time' => $existingOtp->expire_time,
      ], 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }

  public function resendOtp()
  {
    DB::beginTransaction();
    try {
      $userId = Auth::id();
      $detailUser = $this->detailUserRepository->findByUserId($userId);

      if (!$detailUser || !$detailUser->phone) {
        return ResponseHelpers::sendError('Phone number not found for user', [], 404);
      }

      $newOtp = $this->generateAndSendOtp($userId, $detailUser->phone);

      DB::commit();

      return ResponseHelpers::sendSuccess('OTP resent successfully', $newOtp, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }

  public function verifyPhoneNumber($data, $id)
  {
    try {
      $existingDetail = $this->detailUserRepository->find($id);

      if (!$existingDetail) {
        return ResponseHelpers::sendError('Detail user not found', [], 404);
      }

      $otpVerification = $this->verifyOtp([
        'otp_number' => $data['otp_number']
      ]);

      $responseData = $otpVerification->getData(true);
      if (!$responseData['success']) {
        return $otpVerification;
      }

      DB::beginTransaction();

      $this->detailUserRepository->update($existingDetail->id, ['phone' => $existingDetail->phone]);

      DB::commit();

      return ResponseHelpers::sendSuccess('Phone number updated successfully', [
        'phone' => $existingDetail->phone,
      ], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [$e], 500);
    }
  }
}
