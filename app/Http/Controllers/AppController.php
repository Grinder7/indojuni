<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\User\UserService;
use Illuminate\Support\Str;

class AppController extends Controller
{   
    public UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function home()
    {
        if(!Auth::user()){
            $username = "user_" . (string) time();
            $password = Hash::make($username);
            $this->userService->register(array(
                "username"=> $username,
                "password"=> $password,
                "firstname"=> "user",
                "lastname"=> "user",
                "email"=> $username . "@example.com",
                "address" => "Jl. Mangga no. 1992",
                "city" => "Kab. Sidoarjo",
                "province" => "Jawa Timur",
                "postcode" => 65535,
                "card_name" => "User",
                "card_number" => 123456789123456,
                "card_type" => 1,
                "card_expiration" => "12/34",
                "card_cvv" => "999",
                "is_admin" => FALSE,
            ));
            $throttlekey = Str::transliterate(Str::lower($username));
            $data=$this->userService->login(array("email"=> $username . "@example.com", "password"=>$password), $throttlekey);
        }
        return view('pages.home');
    }
    public function aboutus()
    {
        return view('pages.aboutus');
    }
}
