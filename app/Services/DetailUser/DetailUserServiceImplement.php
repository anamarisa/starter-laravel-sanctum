<?php

namespace App\Services\DetailUser;

use App\Http\Helpers\ResponseHelpers;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\DetailUser\DetailUserRepository;
use App\Services\FonnteService;
use App\Services\Otp\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DetailUserServiceImplement extends ServiceApi implements DetailUserService
{
  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;
  protected $fonnteService;
  protected $otpService;

  public function __construct(DetailUserRepository $mainRepository, FonnteService $fonnteService, OtpService $otpService)
  {
    $this->mainRepository = $mainRepository;
    $this->fonnteService = $fonnteService;
    $this->otpService = $otpService;
  }

  public function createDetailUser($data)
  {
    try {
      $userId = Auth::id();
      $data['user_id'] = $userId;
      $validatePhoneNumber = $this->fonnteService->checkNumber($data['phone_number']);

      if (!empty($validatePhoneNumber['not_registered'])) {
        return ResponseHelpers::sendError('Phone number not registered: ', $validatePhoneNumber, 400);
      }

      DB::beginTransaction();

      $detailUser = $this->mainRepository->create([
        'user_id' => $userId,
        'phone_number' => $data['phone_number'],
      ]);

      if (!$detailUser || !$detailUser->phone_number) {
        return ResponseHelpers::sendError('Failed to save phone number', [], 400);
      }

      $this->otpService->generateAndSendOtp($userId, $detailUser->phone_number);

      DB::commit();

      return ResponseHelpers::sendSuccess('Phone number added successfully', $detailUser, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong while adding phone number', [$e->getMessage()], 500);
    }
  }

  public function getDetailById($id)
  {
    try {

      $detail = $this->mainRepository->find($id);

      if (!$detail) {
        return ResponseHelpers::sendError('Detail user not found', null, 404);
      }

      return ResponseHelpers::sendSuccess('Detail user fetched successfully', $detail, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e], 500);
    }
  }

  public function updateDetailUser($id, $data, $image = null)
  {
    DB::beginTransaction();
    try {
      $userId = Auth::id();

      $existingDetail = $this->mainRepository->find($id);

      if (!$existingDetail) {
        DB::rollBack();
        return ResponseHelpers::sendError('Detail user not found', null, 404);
      }

      if ($existingDetail->user_id !== $userId) {
        DB::rollBack();
        return ResponseHelpers::sendError('Unauthorized: You cannot update this user\'s details', null, 403);
      }

      if (isset($data['phone_number']) && $data['phone_number'] != $existingDetail->phone_number) {
        $validatePhoneNumber = $this->fonnteService->checkNumber($data['phone_number']);
        if (!empty($validatePhoneNumber['not_registered'])) {
          DB::rollBack();
          return ResponseHelpers::sendError('Phone number not registered', $validatePhoneNumber, 400);
        }

        // Generate and send OTP, store the new phone number temporarily
        $this->otpService->generateAndSendOtp($userId, $data['phone_number']);
        $data['pending_phone_number'] = $data['phone_number'];
        unset($data['phone_number']);
      }

      // Handle img upload
      if ($image) {
        if ($existingDetail && $existingDetail->img_url && Storage::exists('public/' . $existingDetail->img_url)) {
          Storage::delete('public/' . $existingDetail->img_url);
        }
        $imagePath = $image->store('detail-user', 'public');
        $data['img_url'] = $imagePath;
      }

      $this->mainRepository->update($id, $data);

      DB::commit();

      $detail = $this->mainRepository->find($id);

      return ResponseHelpers::sendSuccess('Detail user updated successfully', $detail, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [$e], 500);
    }
  }
}
