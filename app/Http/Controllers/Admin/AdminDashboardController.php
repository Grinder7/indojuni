<?php

namespace App\Http\Controllers\Admin;

use App\Modules\Product\ProductService;
use App\Modules\ShoppingCart\OrderDetail\OrderDetailService;
use App\Modules\User\UserService;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public ProductService $productService;
    public OrderDetailService $orderDetailService;
    public UserService $userService;
    public function __construct(ProductService $productService, OrderDetailService $orderDetailService, UserService $userService)
    {
        $this->productService = $productService;
        $this->orderDetailService = $orderDetailService;
        $this->userService = $userService;
    }

    public function index()
    {
        $monthlySales = $this->orderDetailService->getThisMonthSalesAmount();
        $userCount = $this->userService->getUserCount();
        $productCount = $this->productService->getProductCount();
        $transactionCount = $this->orderDetailService->getTransactionCount();
        $fiveLatestOrders = $this->orderDetailService->getFiveLatestOrders();
        return view('pages.admin.dashboard', compact('monthlySales', 'userCount', 'productCount', 'transactionCount', 'fiveLatestOrders'));
    }
}
