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
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {
            $success = $this->userService->register($validated);

            if (!$success) {
                return $request->expectsJson()
                    ? response()->json([
                        'status' => 400,
                        'message' => 'Register failed',
                        'data' => null
                    ], 400)
                    : back()->withErrors(['register' => 'Register failed'])->withInput();
            }
        } catch (\Exception $e) {
            return $request->expectsJson()
                ? response()->json([
                    'status' => 400,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'data' => null
                ], 400)
                : back()->withErrors(['register' => 'An error occurred: ' . $e->getMessage()])->withInput();
        }

        return $request->expectsJson()
            ? response()->json([
                'status' => 200,
                'message' => 'Successfully registered',
                'data' => null
            ], 200)
            : redirect()->route('app.login.page')->with('success', 'Successfully registered');
    }
}
