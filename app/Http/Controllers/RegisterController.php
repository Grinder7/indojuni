<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Modules\User\UserService;

class RegisterController extends Controller
{
    public UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        return view('pages.auth.register');
    }
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        $success = $this->userService->register($validated);
        if ($success) {
            return redirect(route('login.page'))->with('success', 'Successfully register account!');
        } else {
            return redirect(route('register.page'))->with('error', 'Register failed');
        }
    }
}
