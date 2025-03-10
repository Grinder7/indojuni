<?php

declare(strict_types=1);

namespace App\Modules\ShoppingCart\ShoppingSession;

use App\Models\ShoppingSession;

class ShoppingSessionService
{
    public ShoppingSessionRepository $shoppingSessionRepository;
    public function __construct(ShoppingSessionRepository $shoppingSessionRepository)
    {
        $this->shoppingSessionRepository = $shoppingSessionRepository;
    }
    public function getAllData()
    {
        return $this->shoppingSessionRepository->getAllData();
    }
    public function create(array $data): ShoppingSession
    {
        return $this->shoppingSessionRepository->create($data);
    }
    public function getByUserId(string $uid): ShoppingSession
    {
        return $this->shoppingSessionRepository->getByUserId($uid);
    }
    public function deleteByUserId(string $uid): bool
    {
        return $this->shoppingSessionRepository->deleteByUserId($uid);
    }
    public function updateTotal(string $uid, int $total): bool
    {
        return $this->shoppingSessionRepository->updateTotal($uid, $total);
    }
    public function deleteById(string $id): bool
    {
        return $this->shoppingSessionRepository->deleteById($id);
    }
}
