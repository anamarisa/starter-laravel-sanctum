<?php

namespace App\Services\Category;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Category\CategoryRepository;
use App\Http\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryServiceImplement extends ServiceApi implements CategoryService
{

  /**
   * set title message api for CRUD
   * @param string $title
   */
  /**
   * uncomment this to override the default message
   * protected string $create_message = "";
   * protected string $update_message = "";
   * protected string $delete_message = "";
   */

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected CategoryRepository $mainRepository;

  public function __construct(CategoryRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function paginate($data)
  {
    try {
      $limit = $data['limit'] ?? 10;
      $page = $data['page'] ?? 1;

      $keyword = $data['keywords'] ?? null;

      return $this->mainRepository->paginate($limit, $page, $keyword);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong during Paginate Category', [$e->getMessage()], 500);
    }
  }

  public function getAllCategory()
  {
    try {
      $category = $this->mainRepository->findAll();

      return ResponseHelpers::sendSuccess('Category retrieved successfully', $category, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong during All Category', [$e->getMessage()], 500);
    }
  }

  public function getCategoryById($id)
  {
    try {

      $category = $this->mainRepository->findById($id);

      if (is_null($category)) {
        return ResponseHelpers::sendError('Category not found', [], 404);
      }

      return ResponseHelpers::sendSuccess('Category retrieved successfully', $category, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong during Category ID', [$e->getMessage()], 500);
    }
  }

  public function createCategory($data, $image = null)
  {
    try {
      DB::beginTransaction();

      $categoryData = [
        'name' => $data['name'],
        'slug' => Str::slug($data['name']),
      ];

      $category = $this->mainRepository->create($categoryData);
      DB::commit();

      return ResponseHelpers::sendSuccess('Category created successfully', $category, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong during Create Category', [$e->getMessage()], 500);
    }
  }

  public function updateCategory($id, $data, $image = null)
  {
    DB::beginTransaction();
    try {
      $data['slug'] = Str::slug($data['name']);

      $category = $this->mainRepository->update($data, $id);

      if (is_null($category)) {
        return ResponseHelpers::sendError('Category not found', [], 404);
      }

      DB::commit();
      return ResponseHelpers::sendSuccess('Category updated successfully', $category, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong during Updated Category', [$e->getMessage()], 500);
    }
  }

  public function deleteCategory($id)
  {
    DB::beginTransaction();
    try {
      $category = $this->mainRepository->find($id);

      if (is_null($category)) {
        return ResponseHelpers::sendError('Category not found', [], 404);
      }

      $categories = $this->mainRepository->delete($id);

      DB::commit();
      return ResponseHelpers::sendSuccess('Category deleted successfully', $categories, 200);
    } catch (\Exception $e) {
      DB::rollBack();

      return ResponseHelpers::sendError('Something went wrong during Deleted Category', [$e->getMessage()], 500);
    }
  }
}
