<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AdminService;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        public AdminService $service
    ) {}



    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        try {
            $result = $this->service->update($id, $request->validated());
            return response()->success(data: $result);
        }
        catch (\Exception $e) {
            return response()->fail($e->getMessage());
        }
    }


    public function destroy(int $id)
    {
        try {
            return response()->success($this->service->destroy($id));
        }
        catch (\Exception $e) {
            return response()->fail($e->getMessage());
        }
    }
}
