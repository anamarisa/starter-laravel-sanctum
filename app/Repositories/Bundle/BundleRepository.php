<?php

namespace App\Repositories\Bundle;

use LaravelEasyRepository\Repository;

interface BundleRepository extends Repository
{
    public function all();
    public function create($data);
    public function find($id);
    public function update($id, $data);
    public function delete($id);
}
