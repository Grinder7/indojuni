<?php

namespace Database\Seeders;

use App\Models\PaymentDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            "username" => "Admin",
            "email" => "calvinfarrellinok@gmail.com",
            "password" => bcrypt("adminpass"),
            "is_admin" => true,
        ]);

        $user = User::create([
            "username" => "User",
            "email" => "user@gmail.com",
            "password" => bcrypt("userpass"),
            "is_admin" => false,
        ]);

        PaymentDetail::create([
            "user_id" => $user->id,
            "is_default" => true,
            "firstname" => "User",
            "lastname" => "Test",
            "email" => $user->email,
            "address" => "123 Main St",
            "address2" => "Apt 4B",
            "zip" => "12345",
            "payment_method" => "debit",
            "card_name" => "User",
            "card_number" => "1234 5678 9012 3456",
            "card_expiration" => "12/27",
            "card_cvv" => "123",
        ]);
    }
}
