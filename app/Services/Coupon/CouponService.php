<?php

namespace App\Services\Coupon;

use LaravelEasyRepository\BaseService;

interface CouponService extends BaseService
{
    public function getAll();
    public function getAllBin();
    public function findById($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function forceDelete($id);
    public function restore($id);
}
