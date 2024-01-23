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

class AuthService
{

    public function __construct(
        public User $model,
    ) {}

    public function register(array $data): array
    {
        $password = Hash::make($data['password']);

        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $password,
            'language' => $data['language'],
            'can_create_cable' => $data['can_create_cable'],
            'can_update_cable' => $data['can_update_cable'],
        ]);

        return [
            'accessToken' => $this->token($user),
            'user' => $user->toArray()
        ];
    }


    public function login(array $data): array
    {
        $user = $this->model->whereUsername($data['username'])->first();

        if ($user === null) {
            throw new NotFoundException(message:'User not found', code:404);
        }

        if (! Hash::check($data['password'], $user->getAuthPassword())) {
            throw new UnauthorizedException(message:'Unauthorized', code:401);
        }

        return [
            'accessToken' => $this->token($user),
            'user' => new UserLoginResource($user)
        ];
    }

    public function token(object $user): string
    {
        $expiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $accessToken = $user->createToken('access-token', [TokenAbility::ACCESS_TOKEN->value], $expiresAt);

        return $accessToken->plainTextToken;
    }


    public function profile()
    {
        return ['user' => new UserLoginResource(auth()->user())];
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        Auth::guard('web')->logout();
    }
}
