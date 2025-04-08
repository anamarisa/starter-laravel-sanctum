<?php

namespace App\Repositories\Otp;

use LaravelEasyRepository\Repository;

interface OtpRepository extends Repository
{
    public function create($data);
    public function findByUserId($userId);
    public function findByLatest($userId);
}
