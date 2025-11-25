<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index()
    {
        $userdata = Auth::user();

        return view("pages.userprofile", compact("userdata"));
    }

    public function store(UserProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());
        return redirect()->back()->with('success', 'profile successfully updated');
    }
}
