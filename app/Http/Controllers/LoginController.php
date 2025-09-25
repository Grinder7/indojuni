<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Modules\ShoppingSession\ShoppingSessionService;
use App\Modules\User\UserService;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public UserService $userService;
    public ShoppingSessionService $shoppingSessionService;
    public function __construct(UserService $userService, ShoppingSessionService $shoppingSessionService)
    {
        $this->userService = $userService;
        $this->shoppingSessionService = $shoppingSessionService;
    }
    public function index()
    {
        return view('pages.auth.login');
    }
    public function login(LoginRequest $request)
    {
        try {
            $request->checkThrottle();
            $data = $this->userService->login($request->validated(), $request->throttleKey());
            if ($request->expectsJson()) {
                $user = $data["user"];
                $accessToken = $user->createToken('auth_token', ['*'], now()->addMonth(1))->plainTextToken;
                $jsonResponse = [
                    "user" => $user,
                    "shopping_session" => $data["shopping_session"],
                    "access_token" => $accessToken
                ];
                return response()->json([
                    'status' => 200,
                    'message' => 'Login successful',
                    'data' => $jsonResponse
                ], 200);
            }
            return redirect()->route('app.home.page');
        } catch (\Throwable $th) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Login failed: ' . $th->getMessage(),
                    'data' => null
                ], 400);
            }
            return redirect()->back()->withErrors(["errorMessage" => $th->getMessage()]);
        }
    }
    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            // ✅ API logout: revoke token
            $userID = $request->user()->id;
            $this->userService->logout($userID);
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Successfully logged out',
                'data' => null
            ], 200);
        }

        // ✅ Web logout: destroy session
        $userID = Auth::id();
        $this->userService->logout($userID);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('app.home.page');
    }
}
