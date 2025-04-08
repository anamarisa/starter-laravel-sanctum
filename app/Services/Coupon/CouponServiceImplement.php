<?php

namespace App\Services\Coupon;

use App\Http\Helpers\ResponseHelpers;
use LaravelEasyRepository\Service;
use App\Repositories\Coupon\CouponRepository;
use Illuminate\Support\Facades\DB;

class CouponServiceImplement extends Service implements CouponService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(CouponRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function getAll()
  {
    try {
      $coupons = $this->mainRepository->all();

      return ResponseHelpers::sendSuccess('Coupons Fetched successfully', $coupons, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }

  public function getAllBin()
  {
    try {
      $coupon = $this->mainRepository->allBin();

      return ResponseHelpers::sendSuccess('Coupon Fetched successfully', $coupon, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }

  public function findById($id)
  {
    try {
      $coupon = $this->mainRepository->find($id);

      return ResponseHelpers::sendSuccess('Coupon Fetched successfully', $coupon, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }

  public function create($data)
  {
    DB::beginTransaction();
    try {
      $user = auth()->user();

      $coupon = $this->mainRepository->create($data);

      DB::commit();

      return ResponseHelpers::sendSuccess('Coupon created successfully', $coupon, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }

  public function update($id, $data)
  {
    DB::beginTransaction();
    try {
      $coupon = $this->mainRepository->update($id, $data);

      DB::commit();

      return ResponseHelpers::sendSuccess('Coupon updated successfully', $coupon, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }

  public function delete($id)
  {
    DB::beginTransaction();
    try {
      $coupon = $this->mainRepository->find($id);

      if (is_null($coupon)) {
        return ResponseHelpers::sendError('Coupon not found', [], 404);
      }

      $coupon->delete();

      DB::commit();

      return ResponseHelpers::sendSuccess('Coupon deleted successfully', $coupon, 200);
    } catch (\Exception $e) {
      DB::rollBack();

      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }

  public function forceDelete($id)
  {
    DB::beginTransaction();
    try {
      $coupon = $this->mainRepository->forceDelete($id);

      DB::commit();

      return ResponseHelpers::sendSuccess('Coupon deleted successfully', $coupon, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }

  public function restore($id)
  {
    DB::beginTransaction();
    try {
      $coupon = $this->mainRepository->restore($id);

      DB::commit();

      return ResponseHelpers::sendSuccess('Coupon restored successfully', $coupon, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
    }
  }
}
