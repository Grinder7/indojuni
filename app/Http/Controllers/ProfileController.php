<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index(){
        $userID = Auth::id();
        $query_result = User::where('id', $userID)->first();
        $userdata = $query_result;
        
        return view("pages.userprofile", compact("userdata"));
    }

    public function store(UserProfileRequest $request){
        $user = Auth::user();
        $user->update($request->validated());
        return redirect()->back()->with('success', 'profile successfully updated');
    }
}
