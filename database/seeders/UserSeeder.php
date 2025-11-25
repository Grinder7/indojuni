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
            "firstname" => "Calvin",
            "lastname" => "Farrellino",
            "email" => "calvinfarrellinok@gmail.com",
            "password" => bcrypt("adminpass"),
            "address" => "Somewhere in Sidoarjo",
            "city" => "Kab. Sidoarjo",
            "province" => "Jawa Timur",
            "postcode" => 65535,
            "card_name" => "Calvin Farrellino",
            "card_number" => "3141 5926 5358 9793",
            "card_type" => 1,
            "card_expiration" => "03/14",
            "card_cvv" => "271",
            "is_admin" => true,
        ]);

        $user = User::create([
            "username" => "User",
            "email" => "user@gmail.com",
            "password" => bcrypt("userpass"),
            "is_admin" => false,
        ]);
    }
}
