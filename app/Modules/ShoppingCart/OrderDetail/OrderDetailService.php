<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\OrderDetail;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Collection;

class OrderDetailService
{
    public OrderDetailRepository $orderDetailRepository;
    public function __construct(OrderDetailRepository $orderDetailRepository)
    {
        $this->orderDetailRepository = $orderDetailRepository;
    }
    public function getAllData()
    {
        return $this->orderDetailRepository->getAllData();
    }
    public function create(array $data): OrderDetail
    {
        return $this->orderDetailRepository->create($data);
    }
    public function getById(string $id): OrderDetail | null
    {
        return $this->orderDetailRepository->getById($id);
    }
    public function getByUserId(string $id): Collection | null
    {
        return $this->orderDetailRepository->getByUserId($id);
    }
}
