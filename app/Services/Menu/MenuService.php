<?php

namespace App\Services\Menu;

use LaravelEasyRepository\BaseService;

interface MenuService extends BaseService
{
    public function getAllMenu();
    public function getMenuById($id);
    public function createMenu($data);
    public function updateMenu($id, $data);
    public function deleteMenu($id);
}
