<?php

namespace App\Services\Bundle;

use LaravelEasyRepository\BaseService;

interface BundleService extends BaseService
{
    public function getAll();
    public function getById($id);
    public function createBundle($request);
    public function updateBundle($request, $id);
    public function deleteBundle($id);
}
