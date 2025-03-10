<?php

namespace App\Http\Controllers;

use App\Modules\ShoppingCart\OrderDetail\OrderDetailService;
use App\Modules\ShoppingCart\OrderItem\OrderItemService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public OrderDetailService $orderDetailService;

    public function __construct(OrderDetailService $orderDetailService)
    {
        $this->orderDetailService = $orderDetailService;
    }
    public function index()
    {
        $transactions = $this->orderDetailService->getByUserId(auth()->user()->id);
        return view('pages.transaction', compact('transactions'));
    }
}
