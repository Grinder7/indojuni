<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index(){
        $userID = Auth::id();
        $query_result = DB::select("SELECT * from users where id = ?", [$userID]);
        $userdata = $query_result[0];
        
        return view("pages.userprofile", compact("userdata"));
    }
}
