<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModifyCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\ShoppingSessionResource;
use App\Modules\Product\ProductService;
use App\Modules\CartItem\CartItemService;
use App\Modules\ShoppingSession\ShoppingSessionService;
use Auth;

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
    public function modifyShoppingCart(ModifyCartItemRequest $request)
    {
        $validated = $request->validated();
        $response = $this->shoppingSessionService->modifyShoppingCart($validated);

        if (!$response["success"]) {
            if ($request->expectsJson()) {
                return response()->json([
                    "status" => 400,
                    "message" => $response["message"],
                    'data' => $response["data"]
                ], 400);
            }
            return redirect()->back()->with('success', false)->with('data', $response["data"])->with('message', $response["message"]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                "status" => 200,
                "message" => "Shopping cart updated successfully",
                'data' => $response["data"]
            ], 200);
        }
        return redirect()->back()->with('success', true)->with('data', $response["data"])->with('message', 'Berhasil memperbarui keranjang belanja');
    }

    public function addShoppingCart(ModifyCartItemRequest $request)
    {
        $validated = $request->validated();
        $shoppingSession = $this->shoppingSessionService->getShoppingSessionByUserID(Auth::id());

        foreach ($validated["product"] as $item) {
            $this->cartItemService->createCartItem([
                "session_id" => $shoppingSession->id,
                "product_id" => $item["product_id"],
                "quantity" => (int)$item["quantity"]
            ]);
            $product = $this->productService->getProductByID((int)$item["product_id"]);
            $shoppingSession->total += $item["quantity"] * $product->price;
            if (!$shoppingSession->save()) {
                throw new \Exception("Gagal memperbarui sesi belanja.");
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                "status" => 200,
                "message" => "Shopping cart updated successfully",
                'data' => [
                    "success" => $validated["product"],
                    "failed" => null
                ]
            ], 200);
        }
        return redirect()->back()->with('success', 'Berhasil memperbarui keranjang belanja');
    }

    public function getUserCartItems()
    {
        $shoppingCart = $this->shoppingSessionService->getShoppingCartByUserID(Auth::id());
        $shoppingSession = ShoppingSessionResource::make($shoppingCart["shopping_session"]);
        $cartItems = CartItemResource::collection($shoppingCart["cart_items"]);

        return response()->json([
            "status" => 200,
            "message" => "Successfully retrieved cart items",
            'data' => [
                "shopping_session" => $shoppingSession,
                "cart_items" => $cartItems
            ]
        ]);
    }
}
