<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        public AuthService $service
    ) {}



    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->service->login($request->validated());

            return response()->success(data:$result, code: 200);
        }
        catch (NotFoundException $e) {
            return response()->fail(error: $e->getMessage(), code: $e->getCode());
        }
        catch (UnauthorizedException $e) {
            return response()->fail(error: $e->getMessage(), code: $e->getCode());
        }
    }


    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->service->register($request->validated());
            return response()->success(data: $result, code: 201);
        }
        catch (\Exception $e) {
            return response()->fail($e->getMessage());
        }
    }


    public function logout(): JsonResponse
    {
        $this->service->logout();
        return response()->success(data: ['message' => 'Logged out successfully']);
    }


    public function profile()
    {
        try {
            return response()->success($this->service->profile());
        }
        catch (\Exception $e) {
            return response()->fail($e->getMessage());
        }
    }
}
