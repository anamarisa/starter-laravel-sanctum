<?php

namespace App\Repositories\Coupon;

use LaravelEasyRepository\Repository;

interface CouponRepository extends Repository
{
    public function all();
    public function allBin();

    public function find($id);
    public function findByVoucher($voucher);

    public function create($data);
    public function update($id, $data);

    public function delete($id);
    public function forceDelete($id);
    public function restore($id);
}
