<?php

namespace App\Repositories\Menu;

use LaravelEasyRepository\Repository;

interface MenuRepository extends Repository
{
    public function all();
    public function search($search, $perPage = 10);
    public function create($data);
    public function find($id);
    public function update($id, $data);
    public function delete($id);
}
