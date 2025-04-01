<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\CartItem;

use App\Models\CartItem;
use App\Models\ShoppingSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CartItemRepository
{
    public function getAllData(): Collection
    {
        return CartItem::all();
    }
    public function getBySessionAndProductId(string $sId, int $pId): CartItem | null
    {
        return CartItem::where('session_id', $sId)->where('product_id', $pId)->get()->first();
    }
    public function getBySessionId(string $sId): Collection | null
    {
        return CartItem::where('session_id', $sId)->get();
    }
    public function insertProduct(array $data): CartItem
    {
        return DB::transaction(function () use ($data) {
            // Lock the cart item to prevent race conditions
            $product = CartItem::where('product_id', $data['product_id'])
                ->where('session_id', $data['session_id'])
                ->lockForUpdate()
                ->first();

            if ($product) {
                $product->quantity += $data['quantity'];
                $product->save();
                return $product;
            } else {
                return CartItem::create($data);
            }
        });
    }

    public function deleteBySessionId(string $sId): int
    {
        return CartItem::where('session_id', $sId)->delete();
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

    public function updateItem(ShoppingSession $session, array $data, CartItem $cartItem)
    {
        if ($cartItem == null) {
            $cartItem = CartItem::where('session_id', $session->id)
                ->where('product_id', $data['product_id'])
                ->first();
        }
        if ($data['quantity'] <= 0) {
            if (!$cartItem->delete()) {
                throw new \Exception('Failed to delete cart item');
            }
        } else {
            $cartItem->quantity = $data['quantity'];
            if (!$cartItem->save()) {
                throw new \Exception('Failed to update cart item');
            }
        }
    }
}
