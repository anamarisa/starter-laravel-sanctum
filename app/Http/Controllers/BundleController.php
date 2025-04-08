<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseHelpers;
use App\Http\Requests\DeliveryStatus\RequestBundle;
use App\Services\Bundle\BundleService;

class BundleController extends Controller
{
    //
    private $bundleService;

    public function __construct(BundleService $bundleService)
    {
        $this->bundleService = $bundleService;
    }

    public function get()
    {
        try {
            return $this->bundleService->getAll();
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function create(RequestBundle $request)
    {
        try {
            return $this->bundleService->createBundle($request);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function show($id)
    {
        try {
            return $this->bundleService->getById($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function update(RequestBundle $request, $id)
    {
        try {
            return $this->bundleService->updateBundle($request, $id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function delete($id)
    {
        try {
            return $this->bundleService->deleteBundle($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }
}
