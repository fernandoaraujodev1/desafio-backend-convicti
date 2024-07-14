<?php

namespace App\Repositories\Sale;

use Illuminate\Database\Eloquent\Collection;
use LaravelEasyRepository\Repository;

interface SaleRepository extends Repository
{
    public function getSalesWithFilter(array $data): ?Collection;

    public function createSale(array $data): ?object;
}
