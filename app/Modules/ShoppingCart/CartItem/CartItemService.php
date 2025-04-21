<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\CartItem;

use App\Models\CartItem;
use App\Models\ShoppingSession;
use App\Modules\Product\ProductRepository;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use function PHPUnit\Framework\isEmpty;

class CartItemService
{
    public CartItemRepository $cartItemRepository;
    public ShoppingSessionRepository $shoppingSessionRepository;
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
            DB::beginTransaction();
            $product = DB::table('products')->where('id', '=', (int)$item["product_id"])->lockForUpdate()->first();
            if ($product == null) {
                $itemData = [
                    "product_id" => $item["product_id"],
                    "product_name" => null,
                    "product_price" => null,
                    "quantity" => $item["quantity"],
                    "error" => "Product not found"
                ];
                $failedProducts[] = $itemData;
                Log::error("Product not found", [
                    'product_id' => $item["product_id"],
                    'session_id' => $shoppingSession->id,
                    'user_id' => Auth::id()
                ]);
                DB::rollBack();
                continue;
            }
            $itemData = [
                "product_id" => $item["product_id"],
                "product_name" => $product->name,
                "product_price" => $product->price,
                "quantity" => $item["quantity"],
                "error" => null
            ];
            try {
                $cartItem = $this->cartItemRepository->getBySessionAndProductId($shoppingSession->id, (int)$itemData["product_id"]);

                if (($cartItem && ($product->stock < $cartItem->quantity + $itemData["quantity"])) || ($product->stock < $itemData["quantity"])) {
                    throw new \Exception("Insufficient stock");
                }

                // Update total price only after cart itemData is successfully inserted
                $addedProduct = $this->cartItemRepository->insertProduct([
                    "session_id" => $shoppingSession->id,
                    "product_id" => $itemData["product_id"],
                    "quantity" => $itemData["quantity"]
                ]);

                if (!$addedProduct) {
                    throw new \Exception("Failed to add product to cart");
                }

                $shoppingSession->total += $product->price * $itemData["quantity"];
                if (!$shoppingSession->save()) {
                    throw new \Exception("Failed to update shopping session total");
                }

                $successProducts[] = $itemData;
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $itemData["error"] = $e->getMessage();
                $failedProducts[] = $itemData;
                Log::error("Error adding item to cart: " . $e->getMessage(), [
                    'data' => $item,
                    'session_id' => $shoppingSession->id,
                    'user_id' => Auth::id()
                ]);
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

    // for api
    public function updateItems(array $data): array
    {
        $shoppingSession = $this->shoppingSessionRepository->getByUserId(Auth::id());
        $failedProducts = [];
        $successProducts = [];
        foreach ($data["product"] as $item) {
            DB::beginTransaction();
            try {
                $product = $this->productRepository->getById((int)$item["product_id"]);
                if ($product == null) {
                    throw new \Exception("Product not found");
                }
                $cartItem = $this->cartItemRepository->getBySessionAndProductId($shoppingSession->id, (int)$item["product_id"]);
                if ($cartItem == null) {
                    throw new \Exception("Cart item not found");
                }
                if (($item["quantity"] > $product->stock)) {
                    throw new \Exception("Insufficient stock");
                }

                $newTotal = $shoppingSession->total
                    - ($cartItem->quantity * $product->price)
                    + ($item["quantity"] * $product->price);
                if ($newTotal < 0) {
                    throw new \Exception("Total cannot be negative");
                }

                $this->cartItemRepository->updateItem($shoppingSession, $item, $cartItem);

                if (!$this->shoppingSessionRepository->updateTotal(Auth::id(), $newTotal)) {
                    throw new \Exception("Failed to update shopping session total");
                }
                $item["error"] = null;
                $itemData = [
                    "product_id" => $item["product_id"],
                    "product_name" => $product->name,
                    "product_price" => $product->price,
                    "quantity" => $item["quantity"],
                    "error" => null
                ];
                $successProducts[] = $itemData;
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $item["quantity"] = $item["quantity"] ?? null;
                $item["product_name"] = $product->name ?? null;
                $item["product_price"] = $product->price ?? null;
                $item["error"] = $e->getMessage();
                $failedProducts[] = $item;
                Log::error("Error updating item in cart: " . $e->getMessage(), [
                    'data' => $item,
                    'session_id' => $shoppingSession->id,
                    'user_id' => Auth::id()
                ]);
            }
        }
        if (count($failedProducts) > 0) {
            return [
                'success' => false,
                'message' => 'Failed to update item(s) in cart',
                'data' => [
                    "failed" => $failedProducts,
                    "success" => $successProducts
                ]
            ];
        }
        return [
            'success' => true,
            'message' => 'Successfully update item(s) in cart',
            'data' => [
                "failed" => $failedProducts,
                "success" => $successProducts
            ]
        ];


        // return $this->cartItemRepository->updateByProductId($data);
    }
}
