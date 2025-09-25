<?php

declare(strict_types=1);

namespace App\Modules\OrderDetail;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Collection;

class OrderDetailRepository
{
    function createOrderDetail(array $data): OrderDetail
    {
        return OrderDetail::create($data);
    }
    function getOrderDetailByID(string $orderDetailID): ?OrderDetail
    {
        return OrderDetail::find($orderDetailID);
    }
    function getUserTransactions(string $userId): Collection
    {
        return OrderDetail::where('user_id', $userId)->get();
    }
    function getAllTransactions(): Collection
    {
        return OrderDetail::all();
    }
}
