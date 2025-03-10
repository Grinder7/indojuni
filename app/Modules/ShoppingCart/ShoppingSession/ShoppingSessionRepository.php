<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\ShoppingSession;

use App\Models\ShoppingSession;
use Illuminate\Database\Eloquent\Collection;

class ShoppingSessionRepository
{
    public function getAllData(): Collection
    {
        return ShoppingSession::all();
    }
    public function create(array $data): ShoppingSession
    {
        return ShoppingSession::create($data);
    }
    public function getByUserId(string $uid): ShoppingSession
    {
        $current_user = ShoppingSession::where('user_id', $uid)->get()->first();
        if (!$current_user) {
            $current_user = $this->create(['user_id' => $uid, 'total' => 0]);
        }
        return $current_user;
    }
    public function deleteByUserId(string $uid): bool
    {
        $current_user = ShoppingSession::where('user_id', $uid)->get()->first();
        if ($current_user) {
            $current_user->delete();
            return true;
        }
        return false;
    }
    public function updateTotal(string $uid, int $total): bool
    {
        $current_user = ShoppingSession::where('user_id', $uid)->get()->first();
        if ($current_user) {
            $current_user->total = $total;
            $current_user->save();
            return true;
        }
        return false;
    }
    public function deleteById(string $id): bool
    {
        $current_user = ShoppingSession::where('id', $id)->get()->first();
        if ($current_user) {
            $current_user->delete();
            return true;
        }
        return false;
    }
}
