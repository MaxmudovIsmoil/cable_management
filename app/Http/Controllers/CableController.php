<?php

namespace App\Http\Controllers;

use App\Http\Requests\CableRequest;
use App\Services\CableService;
use Illuminate\Http\JsonResponse;

class CableController extends Controller
{
    public function __construct(
        public CableService $service
    ) {}


    public function index()
    {
        return response()->success($this->service->list());
    }

    public function getOne(int $id)
    {
        try {
            return response()->success($this->service->one($id));
        }
        catch (\Exception $e) {
            return response()->fail($e->getMessage());
        }
    }

    public function store(CableRequest $request): JsonResponse
    {
        try {
            $result = $this->service->create($request->validated());
            return response()->success($result);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(int $id, CableRequest $request)
    {
        try {
            $result = $this->service->update($id, $request->validated());
            return response()->success($result);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $result = $this->service->destroy($id);
            return response()->success($result);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

}
