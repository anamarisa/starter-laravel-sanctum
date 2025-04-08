<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ResponseHelpers;
use App\Http\Requests\RequestMenu;
use App\Services\Menu\MenuService;
use Illuminate\Http\Request;

class MenutController extends Controller
{
    private $productService;

    public function __construct(MenuService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            return $this->productService->getAllMenu();
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function store(RequestMenu $request)
    {
        try {
            $image = $request->file('img');
            return $this->productService->createMenu($request, $image);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function show($id)
    {
        try {
            return $this->productService->getMenuById($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }

    public function update(RequestMenu $request, $id)
    {
        try {
            $image = $request->file('img');
            return $this->productService->updateMenu($id, $request->validated(), $image);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            return $this->productService->deleteMenu($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [], 500);
        }
    }
}
