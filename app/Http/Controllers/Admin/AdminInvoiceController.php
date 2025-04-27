<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\ShoppingCart\OrderDetail\OrderDetailService;

class AdminInvoiceController extends Controller
{
    private OrderDetailService $orderDetailService;

    public function __construct(OrderDetailService $orderDetailService)
    {
        $this->orderDetailService = $orderDetailService;
    }
    public function index()
    {
        $order_details = $this->orderDetailService->getAllData();
        return view('pages.admin.invoices')->with(compact("order_details"));
    }
}
