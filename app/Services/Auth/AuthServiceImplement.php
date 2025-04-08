<?php

namespace App\Services\Auth;

use LaravelEasyRepository\Service;
use Illuminate\Support\Facades\Hash;
use App\Http\Helpers\ResponseHelpers;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthServiceImplement extends Service implements AuthService
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

    public function LoginServices($request)
    {
        try {
            // Find user by email
            $user = $this->mainRepository->findByEmail($request->email);

            if (is_null($user)) {
                return ResponseHelpers::sendError('No user found', [], 404);
            }

            if ($user->is_active == 0) {
                return ResponseHelpers::sendError('User is inactive. Unable to login', $user, 404);
            }

            // Check password
            if (!Hash::check($request->password, $user->password)) {
                return ResponseHelpers::sendError('The provided credentials are incorrect.', [], 401);
            }

            // Generate token using Sanctum
            $token = $user->createToken('authToken')->plainTextToken;
            $role = $user->role;

            return ResponseHelpers::sendSuccess('Login Successful', [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $role
                ],
            ], 200);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong during login', [$e], 500);
        }
    }

    public function RegisterServices($request)
    {
        DB::beginTransaction();
        try {
            $hashedPassword = Hash::make($request->password);

            $user = $this->mainRepository->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPassword,
                'role' => $request->role_id ?? 'user',
            ]);

            DB::commit();

            $token = $user->createToken('authToken')->accessToken;

            return ResponseHelpers::sendSuccess('Registration Successful', [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Logout failed: ' . $e->getMessage(), ['exception' => $e]);

            return ResponseHelpers::sendError('Something went wrong during Register', [$e], 500);
        }
    }

    // Log out the user by revoking their token
    public function LogOutServices($request)
    {
        try {
            // Get the current authenticated user
            $user = Auth::user();

            $request->user()->currentAccessToken()->delete();

            return ResponseHelpers::sendSuccess('Logout successful', [], 200);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong while logging out', [$e->getMessage()], 500);
        }
    }

    // Get the profile details of the authenticated user
    public function ProfileServices($request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            if (!$user) {
                return ResponseHelpers::sendError('User not found', [], 404);
            }

            return ResponseHelpers::sendSuccess('Profile fetched successfully', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], 200);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong while fetching profile', [$e->getMessage()], 500);
        }
    }

    public function deleteUser($request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return ResponseHelpers::sendError('User not found', [], 404);
            }

            if ($user->is_active == 1) {
                $user = $this->mainRepository->updateStatus($user->id);
            }

            // Revoke all of the user's tokens
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            $user = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'is_verfied' => $user->is_verfied,
                'role_id' => $user->role
            ];

            return ResponseHelpers::sendSuccess('Request submitted successfully', $user, 201);
        } catch (\Exception $e) {
            return ResponseHelpers::sendError('Something went wrong', [$e->getMessage()], 500);
        }
    }
}
