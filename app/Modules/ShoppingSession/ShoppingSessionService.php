<?php

declare(strict_types=1);

namespace App\Modules\ShoppingSession;

use App\Models\OrderDetail;
use App\Models\ShoppingSession;
use App\Modules\CartItem\CartItemRepository;
use App\Modules\CartItem\CartItemService;
use App\Modules\OrderDetail\OrderDetailService;
use App\Modules\Product\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShoppingSessionService
{
    private ShoppingSessionRepository $shoppingSessionRepository;
    private CartItemRepository $cartItemRepository;
    private OrderDetailService $orderDetailService;
    private CartItemService $cartItemService;
    private ProductRepository $productRepository;

    public function __construct(ShoppingSessionRepository $shoppingSessionRepository, CartItemRepository $cartItemRepository, OrderDetailService $orderDetailService, CartItemService $cartItemService, ProductRepository $productRepository)
    {
        $this->shoppingSessionRepository = $shoppingSessionRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->orderDetailService = $orderDetailService;
        $this->cartItemService = $cartItemService;
        $this->productRepository = $productRepository;
    }
    public function getShoppingSessionByUserID(string $userId): ShoppingSession
    {
        return $this->shoppingSessionRepository->getShoppingSessionByUserID($userId);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem>
     */
    public function getCartItemsByShoppingID(string $sessionID): Collection
    {
        return $this->cartItemRepository->getCartItemsByShoppingID($sessionID);
    }
    public function modifyShoppingCart(array $data): array
    {
        $shoppingSession = $this->shoppingSessionRepository->getShoppingSessionByUserID(Auth::id());
        $result = $this->cartItemService->modifyCartItems($data["product"], $shoppingSession);
        if (count($result["failedItem"]) > 0) {
            return [
                'success' => false,
                'message' => 'Failed to modify item(s) in cart',
                'data' => [
                    "failed" => $result["failedItem"],
                    "success" => $result["successItem"],
                    "shoppingSession" => $result["shoppingSession"]
                ]
            ];
        }
        return [
            'success' => true,
            'message' => 'Successfully modify item(s) in cart',
            'data' => [
                "failed" => $result["failedItem"],
                "success" => $result["successItem"],
                "shoppingSession" => $result["shoppingSession"]
            ]
        ];
    }

    public function checkout(string $userID, array $billingAddress): OrderDetail
    {
        $shoppingSession = $this->shoppingSessionRepository->getShoppingSessionByUserID($userID);
        $cartItems = $this->cartItemRepository->getCartItemsByShoppingID($shoppingSession->id);
        if ($cartItems->isEmpty()) {
            throw new \Exception('Keranjang belanja kosong');
        }
        DB::beginTransaction();
        try {
            /** @var \App\Models\CartItem $item */
            foreach ($cartItems as $item) {
                $product = $this->productRepository->getProductByID($item["product_id"]);
                if ($product) {
                    if ($product->stock < $item["quantity"]) {
                        throw new \Exception("{$product->name} tidak cukup stok");
                    }
                    $product->stock -= $item["quantity"];
                    if (!$product->save()) {
                        throw new \Exception("{$product->name} gagal diproses");
                    }
                    $item->delete();
                } else {
                    throw new \Exception("Produk tidak ditemukan");
                }
            }

            $orderDetail = $this->orderDetailService->createInvoice([
                'user_id' => $userID,
                'total' => $shoppingSession->total,
                'billing_address' => $billingAddress,
                'items' => $cartItems->toArray(),
            ]);
            // remove shoppingCart
            $shoppingSession->delete();
            $this->shoppingSessionRepository->createShoppingSession($userID);
            DB::commit();
            return $orderDetail;
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    /**
     * Get a shopping cart with its session and items for the given user.
     *
     * @param string $userID The ID of the user whose shopping cart to retrieve.
     *
     * @return array{
     *     shopping_session: \App\Models\ShoppingSession,
     *     cart_items: \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem>
     * } If no shopping session is found, returns an empty array.
     */
    public function getShoppingCartByUserID(string $userID): array
    {
        $shoppingSession = $this->shoppingSessionRepository->getShoppingSessionByUserID($userID);
        if (!$shoppingSession) {
            return [];
        }
        $cartItems = $this->cartItemRepository->getCartItemsByShoppingID($shoppingSession->id);
        return [
            'shopping_session' => $shoppingSession,
            'cart_items' => $cartItems
        ];
    }
}
