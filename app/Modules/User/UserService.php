<?php

declare(strict_types=1);

namespace App\Modules\User;

use App\Models\User;
use App\Modules\ShoppingSession\ShoppingSessionRepository;
use App\Modules\User\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class UserService
{
    private UserRepository $userRepository;
    private ShoppingSessionRepository $shoppingSessionRepository;
    public function __construct(UserRepository $userRepository, ShoppingSessionRepository $shoppingSessionRepository)
    {
        $this->userRepository = $userRepository;
        $this->shoppingSessionRepository = $shoppingSessionRepository;
    }
    public function login(array $validated, string $throttleKey): array
    {
        $credentials = [
            "email" => $validated["email"],
            "password" => $validated["password"]
        ];
        $rememberMe = boolval($validated["remember-me"] ?? 0);
        $success = Auth::attempt($credentials, $rememberMe);
        if (!$success) {
            $throttleKey && RateLimiter::hit($throttleKey);
            throw new \Exception('Kredensial tidak valid');
        }
        $shoppingSession = $this->shoppingSessionRepository->getShoppingSessionByUserID(Auth::id());
        if (!$shoppingSession) {
            $shoppingSession = $this->shoppingSessionRepository->createShoppingSession(Auth::id());
        }
        session()->regenerate();
        $throttleKey && RateLimiter::clear($throttleKey);
        return [
            'user' => Auth::user(),
            'shopping_session' => $shoppingSession
        ];
    }
    public function register(array $data): bool
    {
        $user = $this->userRepository->getUserByEmail($data['email']);
        if ($user) {
            throw new \Exception('Email sudah terdaftar');
        }
        $data['password'] = Hash::make($data['password']);
        $createdUser = $this->userRepository->createUser($data);
        event(new Registered($createdUser));
        return $createdUser != null;
    }

    public function logout(string $userID): bool
    {
        $this->shoppingSessionRepository->deleteShoppingSessionByUserID($userID);
        return true;
    }

    public function getUserByEmail(string $email): User | null
    {
        return $this->userRepository->getUserByEmail($email);
    }
}
