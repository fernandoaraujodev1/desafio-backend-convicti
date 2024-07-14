<?php

namespace App\Services\Sale;

use Illuminate\Http\JsonResponse;
use LaravelEasyRepository\BaseService;

interface SaleService extends BaseService
{
    public function getSales(array $data): JsonResponse;

    public function getSalesFromSeller(array $data): JsonResponse;

    public function placeSale(array $request): JsonResponse;
}
