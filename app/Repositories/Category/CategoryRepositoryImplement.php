<?php

namespace App\Repositories\Category;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Category;

class CategoryRepositoryImplement extends Eloquent implements CategoryRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Category $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function paginate($limit, $page, $keyword)
    {
        return $this->model->orderBy('sort', 'desc')
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function findAll()
    {
        return $this->model->orderBy('sort', 'asc')->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $id)
    {
        $category = $this->model->find($id);

        if (is_null($category)) {
            return null;
        }

        return $category->update($data);
    }

    public function delete($id)
    {
        $category = $this->model->find($id);

        $category->delete();

        return $category;
    }
}
