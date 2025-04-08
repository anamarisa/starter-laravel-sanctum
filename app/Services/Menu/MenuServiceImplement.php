<?php

namespace App\Services\Menu;

use App\Http\Helpers\ResponseHelpers;
use App\Http\Helpers\FileStorageLocal;
use LaravelEasyRepository\Service;
use Illuminate\Support\Str;
use App\Repositories\Menu\MenuRepository;
use App\Services\Menu\MenuService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuServiceImplement extends Service implements MenuService
{
  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected MenuRepository $mainRepository;

  public function __construct(MenuRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function getAllMenu()
  {
    try {
      $menu = $this->mainRepository->all();

      if (is_null($menu)) {
        return ResponseHelpers::sendError('No data was found', [], 404);
      }

      return ResponseHelpers::sendSuccess('Menu fetched successfully', $menu, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e], 500);
    }
  }

  public function getMenuById($id)
  {
    try {
      $menu = $this->mainRepository->find($id);

      if (is_null($menu)) {
        return ResponseHelpers::sendError('No data was found', [], 404);
      }

      return ResponseHelpers::sendSuccess('Menu fetched successfully', $menu, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e], 500);
    }
  }

  public function createMenu($data, $image = null)
  {
    DB::beginTransaction();
    try {
      $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

      $imageUrl = null;
      if ($image) {
        // Use FileStorageLocal to store the file and retrieve its URL
        $imageUrl = FileStorageLocal::storeFile(
          $image,
          'menu',
          $data['name']
        );
      }

      $menuData = [
        'name' => $data['name'],
        'slug' => $data['slug'],
        'category_id' => $data['category_id'],
        'price' => $data['price'],
        'desc' => $data['desc'],
        'priority' => $data['priority'],
        'img' => $imageUrl,
        'qty' => $data['qty'],
        'status' => $data['status']
      ];

      $menu = $this->mainRepository->create($menuData);

      DB::commit();

      return ResponseHelpers::sendSuccess('Menu created successfully', $menu, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [$e], 500);
    }
  }

  public function updateMenu($id, $data, $image = null)
  {
    DB::beginTransaction();
    try {
      $existingMenu = $this->mainRepository->find($id);

      $data['slug'] = Str::slug($data['name']);

      if ($image) {
        // Delete existing image if it exists
        $existingMenu = $this->mainRepository->find($id);

        if (is_null($existingMenu)) {
          return ResponseHelpers::sendError('Menu not found', [], 404);
        }

        if ($existingMenu && $existingMenu->img && Storage::exists('public/' . $existingMenu->img)) {
          Storage::delete('public/' . $existingMenu->img);
        }

        // Store new image
        $imageUrl = FilestorageLocal::updateFile($existingMenu->img, $image, 'menu', $data['name']);
        $data['img'] = $imageUrl;
      }

      $updated = $this->mainRepository->update($id, $data);

      if (!$updated) {
        DB::rollBack();
        return ResponseHelpers::sendError('Failed to update menu', [], 404);
      }

      DB::commit();

      $menu = $this->mainRepository->find($id);

      return ResponseHelpers::sendSuccess('Menu updated successfully', $menu, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }

  public function deleteMenu($id)
  {
    DB::beginTransaction();
    try {
      $menu = $this->mainRepository->find($id);

      if (!$menu) {
        DB::rollBack();
        return ResponseHelpers::sendError('Menu not found', [], 404);
      }

      if ($menu->img) {
        Storage::disk('public')->delete($menu->img);
      }

      $menu = $this->mainRepository->delete($id);

      DB::commit();

      return ResponseHelpers::sendSuccess('Menu deleted successfully', [], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [$e], 500);
    }
  }
}
