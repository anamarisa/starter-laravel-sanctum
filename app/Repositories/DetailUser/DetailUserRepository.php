<?php

namespace App\Repositories\DetailUser;

use LaravelEasyRepository\Repository;

interface DetailUserRepository extends Repository
{
    public function create($data);
    public function find($id);
    public function update($id, $data);
    public function findByUserId($userId);
    // Write something awesome :)
}
