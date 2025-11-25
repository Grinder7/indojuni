<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentDetailRequest;
use App\Modules\Product\ProductService;
use App\Modules\CartItem\CartItemService;
use App\Modules\ShoppingSession\ShoppingSessionService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public ShoppingSessionService $shoppingSessionService;
    public CartItemService $cartItemService;
    public ProductService $productService;

    public function __construct(ShoppingSessionService $shoppingSessionService, CartItemService $cartItemService, ProductService $productService)
    {
        $this->shoppingSessionService = $shoppingSessionService;
        $this->cartItemService = $cartItemService;
        $this->productService = $productService;
    }

    public function index()
    {
        $shoppingSession = $this->shoppingSessionService->getShoppingSessionByUserID(Auth::id());
        $cartItems = $this->shoppingSessionService->getCartItemsByShoppingID($shoppingSession->id);
        $items = [];
        if ($cartItems) {
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                    'total' => $product->price * $cartItem->quantity
                ];
            }
        }
        $totalItems = count($items);

        # Billing
        $userdata = Auth::user();

        return view('pages.checkout')->with(compact('shoppingSession'))->with(compact('items'))->with(compact('totalItems'))->with(compact('userdata'));
    }

    public function checkout(PaymentDetailRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        try {
            $orderDetail = $this->shoppingSessionService->checkout(Auth::id(), $validated);
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Checkout successful',
                    'data' => $orderDetail
                ], 200);
            }
            return redirect()->route('app.invoice.invoice', ['id' => $orderDetail->id])
                ->with('success', 'Checkout successful');
        } catch (\Throwable $th) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Checkout failed: ' . $th->getMessage(),
                    'data' => null
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Checkout failed: ' . $th->getMessage());
        }
    }
}
