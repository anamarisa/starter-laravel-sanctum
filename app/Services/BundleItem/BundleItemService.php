<?php

namespace App\Services\BundleItem;

use LaravelEasyRepository\BaseService;

interface BundleItemService extends BaseService
{
    public function createBundleItem($request);
    public function updateBundleItem($request);
    public function deleteBundleItem($id);
}
