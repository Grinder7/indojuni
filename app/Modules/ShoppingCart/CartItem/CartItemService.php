<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\CartItem;

use App\Models\CartItem;
use App\Models\ShoppingSession;
use App\Modules\Product\ProductRepository;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CartItemService
{
    public CartItemRepository $cartItemRepository;
    // Get shopping cart session
    public ShoppingSessionRepository $shoppingSessionRepository;
    // Get product 
    public ProductRepository $productRepository;
    public function __construct(CartItemRepository $cartItemRepository, ShoppingSessionRepository $shoppingSessionRepository, ProductRepository $productRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->shoppingSessionRepository = $shoppingSessionRepository;
        $this->productRepository = $productRepository;
    }
    public function getAllData(): Collection
    {
        return $this->cartItemRepository->getAllData();
    }
    public function getBySessionAndProductId(string $sId, int $pId): CartItem | null
    {
        return $this->cartItemRepository->getBySessionAndProductId($sId, $pId);
    }
    public function getBySessionId(string $sId): Collection | null
    {
        return $this->cartItemRepository->getBySessionId($sId);
    }

    public function addProduct(array $data): JsonResponse
    {
        $shoppingSession = $this->shoppingSessionRepository->getByUserId(Auth::id());
        $data['session_id'] = $shoppingSession->id;
        $cartItem = $this->cartItemRepository->getBySessionAndProductId($shoppingSession->id, (int)$data['product_id']);
        // Check if stock is available
        $product = $this->productRepository->getById((int)$data['product_id']);
        if (($cartItem != null) && ($product->stock < $cartItem->quantity + $data['quantity'])) {
            return Response::json([
                'success' => false,
                'message' => 'Stock is not available'
            ], 400);
        }
        // Update total price in shopping session
        $shoppingSession->total += $product->price * $data['quantity'];
        $success = $shoppingSession->save();
        if (!$success) {
            return Response::json([
                'success' => false,
                'message' => 'Failed to add to cart',
                'data' => $data
            ], 400);
        }
        // Add product to cart
        $addedProduct = $this->cartItemRepository->insertProduct($data);
        if (!$addedProduct) {
            return Response::json([
                'success' => false,
                'message' => 'Failed to add to cart',
                'data' => $data
            ], 400);
        } else {
            return Response::json([
                'success' => true,
                'message' => 'Successfully add to cart',
                'data' => $addedProduct
            ], 200);
        }
    }
    public function deleteBySessionId(string $sId): int
    {
        return $this->cartItemRepository->deleteBySessionId($sId);
    }
}
