<?php

declare(strict_types=1);

namespace App\Modules\User;

use App\Models\User;

class UserRepository
{
    public function getUserByEmail(string $email): User | null
    {
        return User::where('email', $email)->first();
    }
    public function createUser(array $data): User
    {
        return User::create($data);
    }
}
