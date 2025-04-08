<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ResponseHelpers;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            return $this->userService->getAllUser();
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
        }
    }
}
