<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\OrderItem;

use App\Models\OrderItem;

class OrderItemService
{
    public OrderItemRepository $orderItemRepository;
    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }
    public function getAllData()
    {
        return $this->orderItemRepository->getAllData();
    }
    public function create(array $data)
    {
        return $this->orderItemRepository->create($data);
    }
    public function getById(string $id)
    {
        return $this->orderItemRepository->getById($id);
    }
    public function getByDetailId(string $id)
    {
        return $this->orderItemRepository->getByDetailId($id);
    }
}
