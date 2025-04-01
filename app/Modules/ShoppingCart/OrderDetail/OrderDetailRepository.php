<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\OrderDetail;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Collection;

class OrderDetailRepository
{
    public function getAllData(): Collection
    {
        return OrderDetail::all();
    }
    public function create(array $data): OrderDetail
    {
        return OrderDetail::create($data);
    }
    public function getByUserId(string $id): Collection | null
    {
        return OrderDetail::where('user_id', $id)->get();
    }
    public function getById(string $id): OrderDetail | null
    {
        return OrderDetail::find($id);
    }
    public function getThisMonthSalesAmount()
    {
        return OrderDetail::whereMonth('created_at', now()->month)->sum('total');
    }
    public function getLastWeekTransactions()
    {
        return OrderDetail::whereBetween('created_at', [now()->subWeek(), now()])->get();
    }
    public function getTransactionCount()
    {
        return OrderDetail::count();
    }
    public function getFiveLatestOrders()
    {
        return OrderDetail::orderBy('created_at', 'desc')->take(5)->get();
    }
}
