<?php

namespace App\Repositories\Bundle;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Bundle;

class BundleRepositoryImplement extends Eloquent implements BundleRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Bundle $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
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
        $bundle = $this->model->find($id);

        if (is_null($bundle)) {
            return null;
        }

        return $bundle->update($data);
    }

    public function delete($id)
    {
        $bundle = $this->model->find($id);

        if (is_null($bundle)) {
            return null;
        }

        return $bundle->delete();
    }
}
