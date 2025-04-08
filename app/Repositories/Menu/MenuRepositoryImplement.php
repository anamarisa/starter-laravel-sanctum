<?php

namespace App\Repositories\Menu;

use App\Models\Menu;
use App\Repositories\Menu\MenuRepository;
use LaravelEasyRepository\Implementations\Eloquent;

class MenuRepositoryImplement extends Eloquent implements MenuRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Menu $model;

    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    public function all($perPage = 10)
    {
        return $this->model->orderBy('sort', 'asc')->get($perPage);
    }

    public function search($search, $perPage = 10)
    {
        //
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->with('category')->find($id);
    }

    public function update($id, $data)
    {
        $menu = $this->model->find($id);

        if (is_null($menu)) {
            return null;
        }

        return $menu->update($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
