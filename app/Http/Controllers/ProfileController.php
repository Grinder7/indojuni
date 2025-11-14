<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index(){
        $userID = Auth::id();
        $query_result = User::where('id', $userID)->first();
        $userdata = $query_result;
        
        return view("pages.userprofile", compact("userdata"));
    }

    public function save_profile(){

    }
}
