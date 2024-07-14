<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use LaravelEasyRepository\Implementations\Eloquent;

class SaleRepositoryImplement extends Eloquent implements SaleRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     *
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    public function getSalesWithFilter(array $data): ?Collection
    {
        $query = $this->model->newQuery();

        $query->when(!empty($data['initial_date']), function ($query) use ($data) {
            $query->where('created_at', '>=', $data['initial_date']);
        });

        $query->when(!empty($data['final_date']), function ($query) use ($data) {
            $query->where('created_at', '<=', $data['final_date']);
        });

        $query->when(!empty($data['seller_id']), function ($query) use ($data) {
            $query->where('user_id', $data['seller_id']);
        });

        $query->when(!empty($data['unity_id']), function ($query) use ($data) {
            $query->whereHas('user.unities', function ($query) use ($data) {
                $query->where('unity_id', $data['unity_id']);
            });
        });

        $query->when(!empty($data['directorship_id']), function ($query) use ($data) {
            $query->whereHas('user', function ($query) use ($data) {
                $query->whereHas('unities', function ($query) use ($data) {
                    $query->where('directorship_id', $data['directorship_id']);
                });
            });
        });

        return $query->with(['user', 'unity'])->get();
    }

    public function createSale(array $data): ?object
    {
        return $this->model->create($data);
    }
}
