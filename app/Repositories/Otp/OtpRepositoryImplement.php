<?php

namespace App\Repositories\Otp;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Otp;

class OtpRepositoryImplement extends Eloquent implements OtpRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Otp $model;

    public function __construct(Otp $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function findByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function findByLatest($userId)
    {
        return $this->model->where('user_id', $userId)->latest('created_at')->first();
    }
}
