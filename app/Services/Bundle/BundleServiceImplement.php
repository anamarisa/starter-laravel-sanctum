<?php

namespace App\Services\Bundle;

use App\Http\Helpers\ResponseHelpers;
use LaravelEasyRepository\Service;
use App\Repositories\Bundle\BundleRepository;
use Illuminate\Support\Facades\DB;

class BundleServiceImplement extends Service implements BundleService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(BundleRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function getAll()
  {
    try {
      $data = $this->mainRepository->all();

      if (is_null($data)) {
        return ResponseHelpers::sendError('No data was found', [], 404);
      }

      return ResponseHelpers::sendSuccess('Success fetch data', $data, 200);
    } catch (\Exception $e) {
      // Handle the exception and return a response
      return ResponseHelpers::sendError('Something went 123', [], 500);
    }
  }

  public function getById($id)
  {
    try {
      $data = $this->mainRepository->find($id);

      if (is_null($data)) {
        return ResponseHelpers::sendError('No data was found', [], 404);
      }

      return ResponseHelpers::sendSuccess('Success fetch data', $data, 200);
    } catch (\Exception $e) {
      // Handle the exception and return a response
      return ResponseHelpers::sendError('Something went wrong', [], 500);
    }
  }

  public function createBundle($request)
  {
    DB::beginTransaction();

    try {
      $data = $this->mainRepository->create($request);

      if (is_null($data)) {
        DB::rollBack(); // Rollback the transaction if no data was found
        return ResponseHelpers::sendError('No data was found', [], 404);
      }

      DB::commit();

      return ResponseHelpers::sendSuccess('Successfully created data', $data, 200);
    } catch (\Exception $e) {
      DB::rollBack();

      return ResponseHelpers::sendError('Something went wrong', [], 500);
    }
  }

  public function updateBundle($request, $id)
  {
    DB::beginTransaction();

    try {
      $bundle = $this->mainRepository->update($id, $request->all());

      if (!$bundle) {
        DB::rollBack();
        return ResponseHelpers::sendError('Bundle update failed', [], 500);
      }

      DB::commit();

      return ResponseHelpers::sendSuccess('Successfully updated bundle', $bundle, 200);
    } catch (\Exception $e) {
      DB::rollBack();

      return ResponseHelpers::sendError('Something went wrong', [], 500);
    }
  }

  public function deleteBundle($id)
  {
    DB::beginTransaction();

    try {
      $bundle = $this->mainRepository->delete($id);

      if (!$bundle) {
        DB::rollBack();
        return ResponseHelpers::sendError('Bundle delete failed', [], 500);
      }

      DB::commit();

      return ResponseHelpers::sendSuccess('Bundle deleted successfully', $bundle, 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseHelpers::sendError('Something went wrong', [], 500);
    }
  }
}
