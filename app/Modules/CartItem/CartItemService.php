<?php

declare(strict_types=1);

namespace App\Modules\CartItem;

use App\Models\CartItem;
use App\Models\ShoppingSession;
use App\Modules\Product\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartItemService
{

    public CartItemRepository $cartItemRepository;
    public ProductRepository $productRepository;
    public function __construct(CartItemRepository $cartItemRepository, ProductRepository $productRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->productRepository = $productRepository;
    }

    public function modifyCartItems(array $cartItems, ShoppingSession $shoppingSession): array
    {
        $failedProducts = [];
        $successProducts = [];
        foreach ($cartItems as $item) {
            DB::beginTransaction();
            try {
                $product = $this->productRepository->getProductByID((int)$item["product_id"]);
                if ($product == null) {
                    throw new \Exception("Produk tidak ditemukan");
                }
                if ((int)$item["quantity"] < 1) {
                    $cartItem = $this->cartItemRepository->getCartItemByProductIDShoppingSessionID((int)$item["product_id"], $shoppingSession->id);
                    if ($cartItem !== null) {
                        $res = $this->cartItemRepository->removeCartItemByID($cartItem->id);
                        if (!$res) {
                            throw new \Exception("Gagal menghapus item keranjang");
                        }
                        $shoppingSession->total -= $cartItem->quantity * $product->price;
                        if (!$shoppingSession->save()) {
                            throw new \Exception("Gagal memperbarui sesi belanja");
                        }
                        $itemData = [
                            "product_id" => (int)$item["product_id"],
                            "product_name" => $product->name,
                            "product_price" => $product->price,
                            "quantity" => (int)$item["quantity"],
                            "error" => null
                        ];
                        $successProducts[] = $itemData;
                    } else {
                        throw new \Exception("Gagal menemukan item keranjang untuk dihapus");
                    }
                } else if ($product->stock > (int)$item["quantity"]) {
                    $cartItem = $this->cartItemRepository->getCartItemByProductIDShoppingSessionID((int)$item["product_id"], $shoppingSession->id);
                    if ($cartItem === null) {
                        $cartItem = $this->cartItemRepository->createCartItem([
                            "session_id" => $shoppingSession->id,
                            "product_id" => (int)$item["product_id"],
                            "quantity" => (int)$item["quantity"]
                        ]);
                        if (!$cartItem) {
                            throw new \Exception("Gagal membuat item keranjang baru");
                        }
                        $shoppingSession->total += $product->price * $item["quantity"];
                        if (!$shoppingSession->save()) {
                            throw new \Exception("Gagal memperbarui sesi belanja");
                        }
                        $itemData = [
                            "product_id" => (int)$item["product_id"],
                            "product_name" => $product->name,
                            "product_price" => $product->price,
                            "quantity" => (int)$item["quantity"],
                            "error" => null
                        ];
                        $successProducts[] = $itemData;
                    } else {
                        $shoppingSession->total = $shoppingSession->total - ($cartItem->quantity * $product->price) + ($item["quantity"] * $product->price);
                        // error_log("Shopping Session Total: " . $shoppingSession->total);
                        if ($shoppingSession->total < 0) {
                            throw new \Exception("Total tidak boleh negatif");
                        }
                        $success = $this->cartItemRepository->updateCartItem($shoppingSession, $item, $cartItem);
                        if (!$success) {
                            throw new \Exception("Gagal memperbarui item keranjang");
                        }
                        if (!$shoppingSession->save()) {
                            throw new \Exception("Gagal memperbarui sesi belanja");
                        }
                        $item["error"] = null;
                        $itemData = [
                            "product_id" => (int)$item["product_id"],
                            "product_name" => $product->name,
                            "product_price" => $product->price,
                            "quantity" => (int)$item["quantity"],
                            "error" => null
                        ];
                        $successProducts[] = $itemData;
                    }
                } else if ($product->stock < (int)$item["quantity"]) {
                    throw new \Exception("Stok tidak cukup");
                } else {
                    throw new \Exception("Kesalahan tidak diketahui");
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $item["quantity"] = (int)$item["quantity"] ?? null;
                $item["product_id"] = (int)$item["product_id"] ?? null;
                $item["product_name"] = $product->name ?? null;
                $item["product_price"] = $product->price ?? null;
                $item["error"] = $e->getMessage();
                $failedProducts[] = $item;
                Log::error("Error updating item in cart: " . $e->getMessage(), [
                    'data' => $item,
                    'session_id' => $shoppingSession->id,
                    'user_id' => Auth::id()
                ]);
                error_log("Error updating item in cart: " . $e->getMessage());
            }
        }
        return [
            "successItem" => $successProducts,
            "failedItem" => $failedProducts,
            "shoppingSession" => $shoppingSession
        ];
    }
    public function getCartItemByProductIDShoppingSessionID(int $productId, string $sessionId)
    {
        return $this->cartItemRepository->getCartItemByProductIDShoppingSessionID($productId, $sessionId);
    }
    public function createCartItem(array $data): CartItem
    {
        return $this->cartItemRepository->createCartItem($data);
    }
}
