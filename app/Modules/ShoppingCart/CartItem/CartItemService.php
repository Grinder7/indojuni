<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\CartItem;

use App\Models\CartItem;
use App\Models\ShoppingSession;
use App\Modules\Product\ProductRepository;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

use function PHPUnit\Framework\isEmpty;

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
        } else if ($product->stock < $data['quantity']) {
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

    // for api
    public function removeItem(array $data): array
    {
        $shoppingSession = $this->shoppingSessionRepository->getByUserId(Auth::id());
        $failedProducts = [];
        $successProducts = [];
        foreach ($data["product_id"] as $productId) {
            $product = $this->productRepository->getById((int)$productId);
            if ($product == null) {
                $failedProducts[] = $productId;
                continue;
            }
            $cartItem = $this->cartItemRepository->getBySessionAndProductId($shoppingSession->id, (int)$productId);
            if ($cartItem == null) {
                $failedProducts[] = $productId;
                continue;
            }
            // Update total price in shopping session
            $shoppingSession->total -= $product->price * $cartItem->quantity;
            $success = $shoppingSession->save();
            if (!$success) {
                $failedProducts[] = $productId;
                continue;
            }
            // Remove product from cart
            $removedProduct = $cartItem->delete();
            if (!$removedProduct) {
                $failedProducts[] = $productId;
            } else {
                $successProducts[] = $productId;
            }
        }
        if (count($failedProducts) > 0) {
            return [
                'success' => false,
                'message' => 'Failed to remove item(s) from cart',
                'data' => [
                    "failed" => $failedProducts,
                    "success" => $successProducts
                ]
            ];
        }
        return [
            'success' => true,
            'message' => 'Successfully remove item(s) from cart',
            'data' => [
                "failed" => $failedProducts,
                "success" => $successProducts
            ]
        ];
    }

    // for api
    public function addItem(array $data): array
    {
        $shoppingSession = $this->shoppingSessionRepository->getByUserId(Auth::id());
        $failedProducts = [];
        $successProducts = [];
        foreach ($data["product"] as $item) {
            $product = $this->productRepository->getById((int)$item["product_id"]);
            if ($product == null) {
                $failedProducts[] = $item["product_id"];
                continue;
            }
            $cartItem = $this->cartItemRepository->getBySessionAndProductId($shoppingSession->id, (int)$item["product_id"]);
            // Check if stock is available
            if (($cartItem != null) && ($product->stock < $cartItem->quantity + $item["quantity"])) {
                $failedProducts[] = $item;
                continue;
            } else if ($product->stock < $item["quantity"]) {
                $failedProducts[] = $item;
                continue;
            }
            // Update total price in shopping session
            $shoppingSession->total += $product->price * $item["quantity"];
            $success = $shoppingSession->save();
            if (!$success) {
                $failedProducts[] = $item;
                continue;
            }
            // Add product to cart
            $addedProduct = $this->cartItemRepository->insertProduct([
                "session_id" => $shoppingSession->id,
                "product_id" => $item["product_id"],
                "quantity" => $item["quantity"]
            ]);

            if (!$addedProduct) {
                $failedProducts[] = $item;
            } else {
                $successProducts[] = $item;
            }
        }
        if (count($failedProducts) > 0) {
            return [
                'success' => false,
                'message' => 'Failed to add item(s) to cart',
                'data' => [
                    "failed" => $failedProducts,
                    "success" => $successProducts
                ]
            ];
        }
        return [
            'success' => true,
            'message' => 'Successfully add item(s) to cart',
            'data' => [
                "failed" => $failedProducts,
                "success" => $successProducts
            ]
        ];
    }

    public function deleteBySessionId(string $sId): int
    {
        return $this->cartItemRepository->deleteBySessionId($sId);
    }
}
