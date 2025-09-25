<?php

declare(strict_types=1);

namespace App\Modules\ShoppingSession;

use App\Models\ShoppingSession;

class ShoppingSessionRepository
{
    public function getShoppingSessionByUserID(string $userID): ShoppingSession
    {
        $shoppingSession = ShoppingSession::where('user_id', $userID)->get()->first();
        if (!$shoppingSession) {
            $shoppingSession = $this->createShoppingSession($userID);
        }
        return $shoppingSession;
    }

    public function createShoppingSession(string $userID): ShoppingSession
    {
        $shoppingSession = new ShoppingSession();
        $shoppingSession->user_id = $userID;
        $shoppingSession->total = 0;
        $shoppingSession->save();
        return $shoppingSession;
    }

    public function deleteShoppingSessionByUserID(string $userID): bool
    {
        $shoppingSession = ShoppingSession::where('user_id', $userID)->first();
        if ($shoppingSession) {
            $shoppingSession->delete();
            return true;
        }
        return false;
    }
}
