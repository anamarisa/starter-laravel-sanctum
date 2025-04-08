<?php

namespace App\Repositories\Category;

use LaravelEasyRepository\Repository;

interface CategoryRepository extends Repository
{
    public function paginate($limit, $page, $keyword);
    public function findAll();
    public function findById($id);
    public function create($data);
    public function update($data, $id);
    public function delete($id);
}
