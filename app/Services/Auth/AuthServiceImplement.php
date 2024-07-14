<?php

namespace App\Services\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use LaravelEasyRepository\Service;

class AuthServiceImplement extends Service implements AuthService
{
    public function login(array $data): JsonResponse
    {
        if (! Auth::attempt($data)) {
            return response()->json(['success' => false, 'message' => 'Credenciais inválidas'], 401);
        }

        $user = auth()->user();

        $user->tokens()->delete();

        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        $accessToken = $user->createToken('access_token', $permissions, now()->addHours(5))->plainTextToken;

        return response()->json(['success' => true, 'access_token' => $accessToken, 'token_type' => 'Bearer']);
    }
}
