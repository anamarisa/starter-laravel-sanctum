<?php

namespace App\Services\DetailUser;

use LaravelEasyRepository\BaseService;

interface DetailUserService extends BaseService
{
    public function createDetailUser($data);
    public function getDetailById($id);
    public function updateDetailUser($id, $data);
}
