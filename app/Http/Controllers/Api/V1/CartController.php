<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\CartItemRequest;
use App\Http\Requests\DeleteCartItemRequest;
use App\Http\Requests\ModifyCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\ShoppingSessionResource;
use App\Modules\ShoppingCart\CartItem\CartItemService;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private CartItemService $cartItemService;
    private ShoppingSessionService $shoppingSessionService;

    public function __construct(CartItemService $cartItemService, ShoppingSessionService $shoppingSessionService)
    {
        $this->cartItemService = $cartItemService;
        $this->shoppingSessionService = $shoppingSessionService;
    }

    private function errorResponse($message = 'Error'): JsonResponse
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
            'data' => null
        ], 400);
    }

    private function successResponse($message = "Success", $data = null): JsonResponse
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function getUserCartItems(): JsonResponse
    {
        $shoppingSession = $this->shoppingSessionService->getByUserId(Auth::id());
        if (!$shoppingSession) {
            return $this->errorResponse('Shopping session not found');
        }

        $cartItems = $this->cartItemService->getBySessionId($shoppingSession->id);
        if (!$cartItems) {
            return $this->errorResponse('Cart items not found');
        }

        return $this->successResponse('Success', [
            "shoppingSession" => ShoppingSessionResource::make($shoppingSession),
            "cartItems" => CartItemResource::collection($cartItems)
        ]);
    }

    public function postAddCartItem(AddCartItemRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $res = $this->cartItemService->addItem($validated);
        if (!$res["success"]) {
            return response()->json([
                'status' => 400,
                'message' => $res["message"],
                'data' => $res["data"]
            ], 200);
        }
        return $this->successResponse($res["message"], $res["data"]);
    }

    public function postRemoveCartItem(DeleteCartItemRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $res = $this->cartItemService->removeItem($validated);
        if (!$res["success"]) {
            return response()->json([
                'status' => 400,
                'message' => $res["message"],
                'data' => $res["data"]
            ], 200);
        }
        return $this->successResponse($res["message"], $res["data"]);
    }

    public function postUpdateCartItem(ModifyCartItemRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $res = $this->cartItemService->updateItems($validated);
        if (!$res["success"]) {
            return response()->json([
                'status' => 400,
                'message' => $res["message"],
                'data' => $res["data"]
            ], 200);
        }
        return $this->successResponse($res["message"], $res["data"]);
    }
}
