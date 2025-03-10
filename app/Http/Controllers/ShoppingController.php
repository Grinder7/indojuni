<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Modules\Product\ProductService;
use App\Modules\ShoppingCart\CartItem\CartItemService;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ShoppingController extends Controller
{
    public CartItemService $cartItemService;
    public ShoppingSessionService $shoppingSessionService;
    public ProductService $productService;
    public function __construct(CartItemService $cartItemService, ShoppingSessionService $shoppingSessionService, ProductService $productService)
    {
        $this->cartItemService = $cartItemService;
        $this->shoppingSessionService = $shoppingSessionService;
        $this->productService = $productService;
    }
    public function storeCart(CartItemRequest $request)
    {
        if (Auth::check()) {
            $validated =  $request->validated();
            // Check Stock Availability
            $product = $this->productService->getById((int)$validated['product_id']);
            if ($product->stock < $validated['quantity']) {
                return Response::json([
                    'success' => false,
                    'message' => 'Stock is not available'
                ], 400);
            }
            $response = $this->cartItemService->addProduct($validated);
            return $response;
        } else {
            return Response::json([
                'success' => false,
                'message' => 'Please login first'
            ], 400);
        }
    }
}
