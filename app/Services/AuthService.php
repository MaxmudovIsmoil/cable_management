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
            'can_create_cable' => $data['can_create_cable']
        ]);

        $accessToken = $this->access_refresh_token($user)->access_token;
        $refreshToken = $this->access_refresh_token($user)->refresh_token;

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
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

        $accessToken = $this->access_refresh_token($user)->access_token;
        $refreshToken = $this->access_refresh_token($user)->refresh_token;

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => new UserLoginResource($user)
        ];
    }

    protected function access_refresh_token(object $user): object
    {
        $expiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $expiresRt = Carbon::now()->addMinutes(config('sanctum.rt_expiration'));

        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], $expiresAt);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], $expiresRt);

        return (object)[
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];
    }

    public function refreshToken(Request $request)
    {
        $expiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $access_token = $request->user()->createToken('access_token', [TokenAbility::ACCESS_API->value], $expiresAt);

        return [
            'access_token' => $access_token->plainTextToken
        ];
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
