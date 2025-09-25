<?php

declare(strict_types=1);

namespace App\Modules\OrderItem;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;

class OrderItemRepository
{
    public function createOrderItem(array $data): OrderItem
    {
        return OrderItem::create($data);
    }
    public function getOrderItemsByOrderID(string $orderId): Collection
    {
        return OrderItem::where('order_id', $orderId)->get();
    }
}
