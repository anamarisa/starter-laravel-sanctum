<?php

namespace App\Services\BundleItem;

use App\Http\Helpers\ResponseHelpers;
use LaravelEasyRepository\Service;
use App\Repositories\BundleItem\BundleItemRepository;
use Illuminate\Support\Facades\DB;

class BundleItemServiceImplement extends Service implements BundleItemService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(BundleItemRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function createBundleItem($request)
  {
    DB::beginTransaction();
    try {

      $data = $this->mainRepository->create($request);

      DB::commit();

      return ResponseHelpers::sendSuccess('Successfully created data', $data, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }

  public function updateBundleItem($request)
  {
    DB::beginTransaction();
    try {
      foreach ($request as $value) {
        $this->mainRepository->update(
          $value['id'],
          [
            'bundle_id' => $value['bundle_id'],
            'menu_id' => $value['menu_id'],
            'qty' => $value['qty']
          ]
        );
      }

      DB::commit();

      return ResponseHelpers::sendSuccess('Successfully created data', [], 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }

  public function deleteBundleItem($id)
  {
    DB::beginTransaction();
    try {
      $this->mainRepository->delete($id);

      DB::commit();
      
      return ResponseHelpers::sendSuccess('Successfully deleted data', [], 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }
}
