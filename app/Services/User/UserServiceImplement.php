<?php

namespace App\Services\User;

use App\Http\Helpers\ResponseHelpers;
use LaravelEasyRepository\Service;
use App\Repositories\User\UserRepository;

class UserServiceImplement extends Service implements UserService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(UserRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function getAllUser()
  {
    try {
      $users = $this->mainRepository->getAllUser();

      if (is_null($users)) {
        return ResponseHelpers::sendError('Users not found', [], 404);
      }

      return ResponseHelpers::sendSuccess('Users fetched successfully', $users, 200);
    } catch (\Exception $e) {
      return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
    }
  }
}
