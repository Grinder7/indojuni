<?php

declare(strict_types=1);

namespace App\Modules\CartItem;

use App\Models\CartItem;
use App\Models\ShoppingSession;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem>
     */
    public function getCartItemsByShoppingID(string $shoppingID): Collection
    {
        return CartItem::where('session_id', $shoppingID)->get();
    }
    public function getCartItemByProductIDShoppingSessionID(int $productID, string $shoppingID): CartItem | null
    {
        return CartItem::where('session_id', $shoppingID)->where('product_id', $productID)->get()->first();
    }
    public function removeCartItemByID(string $cartItemID): bool
    {
        $cartItem = CartItem::find($cartItemID);
        if ($cartItem) {
            return $cartItem->delete();
        }
        return false;
    }
    public function createCartItem(array $data): CartItem
    {
        $cartItem = CartItem::where('product_id', $data['product_id'])
            ->where('session_id', $data['session_id'])
            ->lockForUpdate()
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
            $result = $cartItem;
        } else {
            error_log('Creating new cart item with data: ' . json_encode($data));
            $result = CartItem::create($data);
        }
        return $result;
    }

    /**
     * Update the quantity of a cart item.
     *
     * @param \App\Models\ShoppingSession $session
     * @param array $data {product_id: int, quantity: int}
     * @param \App\Models\CartItem $cartItem optional
     * @return void
     * @throws \Exception
     */

    public function updateCartItem(ShoppingSession $session, array $data, CartItem $cartItem): bool
    {
        if ($cartItem == null) {
            $cartItem = CartItem::where('session_id', $session->id)
                ->where('product_id', $data['product_id'])
                ->first();
        }
        if ($data['quantity'] <= 0) {
            if (!$cartItem->delete()) {
                return false;
            }
        } else {
            $cartItem->quantity = $data['quantity'];
            if (!$cartItem->save()) {
                return false;
            }
        }
        return true;
    }
}
