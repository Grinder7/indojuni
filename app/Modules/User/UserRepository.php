<?php

declare(strict_types=1);

namespace App\Modules\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function insertUser(array $data): User
    {
        return User::create($data);
    }
    public function getAllUser(): Collection
    {
        return User::All();
    }
}
