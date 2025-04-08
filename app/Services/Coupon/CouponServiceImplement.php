<?php

namespace App\Services\Coupon;

use LaravelEasyRepository\Service;
use App\Repositories\Coupon\CouponRepository;

class CouponServiceImplement extends Service implements CouponService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(CouponRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }
}
