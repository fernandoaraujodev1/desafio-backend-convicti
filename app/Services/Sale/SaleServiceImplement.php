<?php

namespace App\Services\Sale;

use App\Enums\Response\ApiResponseMessageEnum;
use App\Jobs\FindClosestUnity;
use App\Repositories\Sale\SaleRepository;
use Illuminate\Http\JsonResponse;
use LaravelEasyRepository\Service;

class SaleServiceImplement extends Service implements SaleService
{
    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    public function __construct(
        protected SaleRepository $mainRepository
    ) {}

    public function getSales(array $data): JsonResponse
    {
        try {
            $sales = $this->mainRepository->getSalesWithFilter($data);

            return response()->json(['success' => true, 'data' => $sales]);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => ApiResponseMessageEnum::FAILURE]);
        }
    }

    public function getSalesFromSeller(array $data): JsonResponse
    {
        try {
            $data['seller_id'] = auth()->user()->id;
            $sales = $this->mainRepository->getSalesWithFilter($data);

            return response()->json(['success' => true, 'data' => $sales]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => ApiResponseMessageEnum::FAILURE]);
        }
    }

    public function placeSale(array $data): JsonResponse
    {
        try {
            $data['user_id'] = auth()->user()->id;
            $sale = $this->mainRepository->createSale($data);

            FindClosestUnity::dispatch($sale);

            return response()->json(['success' => true, 'data' => $sale]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => ApiResponseMessageEnum::FAILURE]);
        }
    }
}
