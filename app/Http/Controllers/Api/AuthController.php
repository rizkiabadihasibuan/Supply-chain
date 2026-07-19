<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\AuthService;
use App\Http\Resources\Authentication\UserResource;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;

class AuthController extends BaseApiController
{
    /**
     * @var AuthService
     */
    protected $AuthService;

    /**
     * Constructor for Dependency Injection
     *
     * @param AuthService $AuthService
     */
    public function __construct(AuthService $AuthService)
    {
        $this->AuthService = $AuthService;
    }

    /**
     * login method
     */
    public function login(LoginRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->AuthService->login(...);
            // return new UserResource($result);
            return $this->sendSuccess('Method login executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute login', [$e->getMessage()], 500);
        }
    }
    /**
     * register method
     */
    public function register(RegisterRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->AuthService->register(...);
            // return new UserResource($result);
            return $this->sendSuccess('Method register executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute register', [$e->getMessage()], 500);
        }
    }
    /**
     * logout method
     */
    public function logout(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->AuthService->logout(...);
            // return new UserResource($result);
            return $this->sendSuccess('Method logout executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute logout', [$e->getMessage()], 500);
        }
    }

}