<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelpers;
use App\Http\Requests\DeliveryStatus\CreateCoupon;
use App\Http\Requests\Coupon\UpdateCoupon;
use App\Services\Coupon\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index()
    {
        try {
            return $this->couponService->getAll();
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            return $this->couponService->findById($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function store(CreateCoupon $request)
    {
        try {
            return $this->couponService->create($request->all());
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function update($id, UpdateCoupon $request)
    {
        try {
            return $this->couponService->update($id, $request->all());
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            return $this->couponService->delete($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function getAllBin()
    {
        try {
            return $this->couponService->getAllBin();
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            return $this->couponService->forceDelete($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function restore($id)
    {
        try {
            return $this->couponService->restore($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }
}
