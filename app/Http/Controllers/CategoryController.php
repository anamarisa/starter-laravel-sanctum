<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RequestCategory;
use App\Services\Category\CategoryService;
use App\Http\Helpers\ResponseHelpers;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function paginate(Request $request)
    {
        try {
            return $this->categoryService->paginate($request->all());
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            return $this->categoryService->getAllCategory();
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            return $this->categoryService->getCategoryById($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    public function store(RequestCategory $request)
    {
        try {
            if (is_null($request->file('img'))) {
                return response()->json(["Validation_Error" => ['img' => ['Image is required']]], 422);
            }

            $image = $request->file('img');
            return $this->categoryService->createCategory($request->validated(), $image);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Failed to create category', ['message' => $e->getMessage()], 500);
        }
    }

    public function update(RequestCategory $request, $id)
    {
        try {
            $image = $request->file('img');
            return $this->categoryService->updateCategory($id, $request->validated(), $image);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Failed to update category', ['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            return $this->categoryService->deleteCategory($id);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Failed to delete category', ['message' => $e->getMessage()], 500);
        }
    }
}
