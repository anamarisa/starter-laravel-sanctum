<?php

namespace App\Repositories\Coupon;

use App\Models\Coupon;
use LaravelEasyRepository\Implementations\Eloquent;

class CouponRepositoryImplement extends Eloquent implements CouponRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Coupon $model;

    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    public function all()
    {
        return $this->model->with('market')->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findByVoucher($voucher)
    {
        return $this->model->where('voucher_code', $voucher)->first();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $coupon = $this->model->find($id);

        if (is_null($coupon)) {
            return null;
        }

        return $coupon->update($data);
    }

    public function delete($id)
    {
        $coupon = $this->model->find($id);

        if (is_null($coupon)) {
            return null;
        }

        return $coupon->delete();
    }

    public function allBin()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function forceDelete($id)
    {
        $coupon = $this->model->find($id);

        if (is_null($coupon)) {
            return null;
        }

        return $coupon->forceDelete();
    }

    public function restore($id)
    {
        $coupon = $this->model->find($id);

        if (is_null($coupon)) {
            return null;
        }

        return $coupon->restore();
    }
}
