<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\CartItem;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;

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
        // Check if product already exist in cart
        $product = CartItem::where('product_id', $data['product_id'])->where('session_id', $data['session_id'])->get()->first();
        if ($product) {
            $product->quantity += $data['quantity'];
            $product->save();
            return $product;
        } else {
            return CartItem::create($data);
        }
    }
    public function deleteBySessionId(string $sId): int
    {
        return CartItem::where('session_id', $sId)->delete();
    }
}
