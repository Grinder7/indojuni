<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentDetailRequest;
use App\Modules\PaymentDetail\PaymentDetailService;
use App\Modules\Product\ProductService;
use App\Modules\ShoppingCart\CartItem\CartItemService;
use App\Modules\ShoppingCart\OrderDetail\OrderDetailService;
use App\Modules\ShoppingCart\OrderItem\OrderItemService;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public PaymentDetailService $paymentDetailService;
    public OrderDetailService $orderDetailService;
    public OrderItemService $orderItemService;
    public ShoppingSessionService $shoppingSessionService;
    public CartItemService $cartItemService;
    public ProductService $productService;
    public function __construct(PaymentDetailService $paymentDetailService, OrderDetailService $orderDetailService, OrderItemService $orderItemService, ShoppingSessionService $shoppingSessionService, CartItemService $cartItemService, ProductService $productService)
    {
        $this->paymentDetailService = $paymentDetailService;
        $this->orderDetailService = $orderDetailService;
        $this->orderItemService = $orderItemService;
        $this->shoppingSessionService = $shoppingSessionService;
        $this->cartItemService = $cartItemService;
        $this->productService = $productService;
    }

    public function confirm(PaymentDetailRequest $request)
    {
        if (Auth::check()) {
            $shoppingSession = $this->shoppingSessionService->getByUserId(Auth::id());
            if ($shoppingSession->total == 0) {
                return redirect()->route('app.checkout')->with('error', 'Your cart is empty');
            } else {
                // Check stock availability
                $cartItems = $this->cartItemService->getBySessionId($shoppingSession->id);
                foreach ($cartItems as $cartItem) {
                    $product = $this->productService->getById($cartItem->product_id);
                    if ($product->stock < $cartItem->quantity) {
                        return redirect()->route('app.checkout')->with('error', 'Stock is not available (Product: ' . $product->name . ', Stock: ' . $product->stock . ')');
                    }
                }
                // Remove stock from product
                foreach ($cartItems as $cartItem) {
                    $product = $this->productService->getById($cartItem->product_id);
                    $product->stock -= $cartItem->quantity;
                    $product->save();
                }
                $validated = $request->validated();
                $remember = $request->has('remember_detail') ? true : false;
                if ($remember == true) {
                    unset($validated['remember_detail']);
                }
                $validated['user_id'] = Auth::id();
                $paymentDetail = $this->paymentDetailService->create($validated);
                // Copy Shopping Session to Order Detail
                $paymentArray = [
                    'user_id' => Auth::id(),
                    'total' => $shoppingSession->total,
                    'payment_id' => $paymentDetail->id,
                ];
                $orderDetail = $this->orderDetailService->create($paymentArray);
                // Copy Cart Item to Order Item

                foreach ($cartItems as $cartItem) {
                    $orderArray = [
                        'order_id' => $orderDetail->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                    ];
                    $this->orderItemService->create($orderArray);
                }
                // Delete Shopping Session
                $this->shoppingSessionService->deleteById($shoppingSession->id);
                // Delete Cart Item
                $this->cartItemService->deleteBySessionId($shoppingSession->id);
                // Regenerate Shopping Session
                $this->shoppingSessionService->create(['user_id' => Auth::id()]);
                // $request = Request::createFromGlobals();
                $request = Request::create(route('app.invoice', $orderDetail->id), 'GET', ['id' => $orderDetail->id, 'user_id' => Auth::id()]);
                // dd($request);
                // $response = app()->handle($request);
                return redirect()->route('app.invoice',  $orderDetail)->with('user_id', Auth::id());
            }
        } else {
            return redirect()->route('login.page');
        }
        return redirect()->route('app.checkout')->with('error', 'Something went wrong');
    }
}
