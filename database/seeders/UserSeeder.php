<?php

namespace Database\Seeders;

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

        User::create([
            "username" => "User",
            "email" => "user@gmail.com",
            "password" => bcrypt("userpass"),
            "is_admin" => false,
        ]);
    }
}
