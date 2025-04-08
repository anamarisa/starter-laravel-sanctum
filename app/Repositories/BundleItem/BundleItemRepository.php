<?php

namespace App\Repositories\BundleItem;

use LaravelEasyRepository\Repository;

interface BundleItemRepository extends Repository
{
    public function all();
    public function create($data);
    public function find($id);
    public function update($id, $data);
    public function delete($id);
}
