<?php

namespace App\Http\Controllers;

use App\Modules\PaymentDetail\PaymentDetailService;
use App\Modules\Product\ProductService;
use App\Modules\ShoppingCart\OrderDetail\OrderDetailService;
use App\Modules\ShoppingCart\OrderItem\OrderItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public OrderDetailService $orderDetailService;
    public OrderItemService $orderItemService;
    public PaymentDetailService $paymentDetailService;
    public ProductService $productService;
    public function __construct(OrderDetailService $orderDetailService, OrderItemService $orderItemService, PaymentDetailService $paymentDetailService, ProductService $productService)
    {
        $this->orderDetailService = $orderDetailService;
        $this->orderItemService = $orderItemService;
        $this->paymentDetailService = $paymentDetailService;
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        if (Auth::check()) {
            $data['order_id'] = $request->id;
            $validator = Validator::make($data, [
                'order_id' => 'required|string|exists:order_details,id',
            ]);
            if ($validator->fails()) {
                $message = $validator->errors()->messages();
                $message = array_values($message)[0][0];
                return redirect()->back()->with('error', $message);
            } else {
                $data['user_id'] = $this->orderDetailService->getById($data['order_id'])->user_id;
                // Check if user is authorized to view this invoice or the admin wants to view the invoice
                if (Auth::id() == $data['user_id'] || Auth::user()->is_admin === 1) {
                    $orderDetail = $this->orderDetailService->getById($data['order_id']);
                    if ($orderDetail) {
                        $orderItems = $this->orderItemService->getByDetailId($orderDetail->id);
                        $paymentDetail = $this->paymentDetailService->getById($orderDetail->payment_id);
                        $products = $this->productService->getAllData();
                        foreach ($orderItems as $orderItem) {
                            $product = $products->only($orderItem->product_id)->first();
                            $productName = $product->name;
                            $productPrice = $product->price;
                            $items[] = array('product_id' => $orderItem->product_id, 'product_name' => $productName, 'quantity' => $orderItem->quantity, 'price' => $productPrice, 'total' => $productPrice * $orderItem->quantity);
                        }
                    } else {
                        return redirect()->back()->with('error', 'Order not found');
                    }
                    return view('pages.invoice')->with(compact('orderItems', 'paymentDetail', 'orderDetail', 'items'));
                } else {
                    return redirect()->back()->with('error', 'You are not authorized to view this invoice');
                }
            }
        } else {
            return redirect()->route('login.page');
        }
    }
}
