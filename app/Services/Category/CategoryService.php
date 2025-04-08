<?php

namespace App\Services\Category;

use LaravelEasyRepository\BaseService;

interface CategoryService extends BaseService
{
    public function paginate($data);
    public function getAllCategory();
    public function getCategoryById($id);
    public function createCategory($data);
    public function updateCategory($data, $id);
    public function deleteCategory($id);
}
