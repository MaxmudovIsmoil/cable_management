<?php

namespace App\Services;

use App\Enums\TokenAbility;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Http\Resources\UserLoginResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminService
{

    public function __construct(
        public User $model,
    ) {}

    public function update(int $id, array $data): array
    {
        $user = $this->model->findOrFail($id);

        if (isset($data['password']) && $data['password']) {
            $user->fill(['password' => Hash::make($data['password'])]);
        }
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'language' => $data['language'],
            'can_create_cable' => $data['can_create_cable'],
            'can_update_cable' => $data['can_update_cable'],
            'update' => now(),
        ]);
        $user->save();

        return $user->toArray();
    }


    public function destroy(int $id)
    {
        $this->model->destroy($id);
        return $id;
    }
}
