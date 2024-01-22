<?php

namespace App\Services;

use App\Http\Resources\CableResource;
use App\Models\Cable;
use Illuminate\Support\Facades\Auth;

class CableService
{

    public function list()
    {
        $cable = Cable::with('user')
            ->orderBy('id', 'DESC')
            ->get();

        return CableResource::collection($cable);
    }

    public function create(array $data)
    {
        return Cable::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'remain_stock' => $data['remain_stock'],
            'purpose' => $data['purpose'],
            'expected_delivery' => $data['expected_delivery'],
        ]);

    }

    public function update(int $id, array $data)
    {
        return Cable::where(['id'=> $id])
            ->update([
                'name' => $data['name'],
                'remain_stock' => $data['remain_stock'],
                'purpose' => $data['purpose'],
                'expected_delivery' => $data['expected_delivery'],
                'updated_at' => now(),
            ]);
    }

    public function destroy(int $id)
    {
        return Cable::destroy($id);
    }

}

