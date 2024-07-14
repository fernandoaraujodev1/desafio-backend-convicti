<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\GetSaleFromSellerRequest;
use App\Http\Requests\Sale\GetSaleRequest;
use App\Http\Requests\Sale\PlaceSaleRequest;
use App\Services\Sale\SaleService;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    public function getSales(GetSaleRequest $request): JsonResponse
    {
        return $this->saleService->getSales($request->validated());
    }

    public function getSalesFromSeller(GetSaleFromSellerRequest $request): JsonResponse
    {
        return $this->saleService->getSalesFromSeller($request->validated());
    }

    public function placeSale(PlaceSaleRequest $request): JsonResponse
    {
        return $this->saleService->placeSale($request->validated());
    }
}
