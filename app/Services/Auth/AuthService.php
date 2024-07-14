<?php

namespace App\Services\Auth;

use Illuminate\Http\JsonResponse;
use LaravelEasyRepository\BaseService;

interface AuthService extends BaseService
{
    public function login(array $data): JsonResponse;
}
