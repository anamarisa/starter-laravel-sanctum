<?php

namespace App\Repositories\DetailUser;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\DetailUser;

class DetailUserRepositoryImplement extends Eloquent implements DetailUserRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected DetailUser $model;

    public function __construct(DetailUser $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($id, $data)
    {
        $detail = $this->find($id);
        return $detail->update($data);
    }

    public function findByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }
    // Write something awesome :)
}
