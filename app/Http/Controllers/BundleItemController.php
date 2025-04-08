<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ResponseHelpers;
use App\Http\Requests\RequestBundleItem;
use App\Http\Requests\RequestBundleItemUpdate;
use App\Services\BundleItem\BundleItemService;
use Illuminate\Http\Request;

class BundleItemController extends Controller
{
    private $bundleItemService;

    public function __construct(BundleItemService $bundleItemService)
    {
        $this->bundleItemService = $bundleItemService;
    }

    public function create(RequestBundleItem $request)
    {
        try {
            return $this->bundleItemService->createBundleItem($request->all());
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function batchUpdate(RequestBundleItemUpdate $request)
    {
        try {
            return $this->bundleItemService->updateBundleItem($request->all());
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function destroy($id)
    {
        try {
            return $this->bundleItemService->deleteBundleItem($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }
}
