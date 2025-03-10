<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionService;
use App\Modules\User\UserService;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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
    public function createCart(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total' => 'integer'
        ]);
        $success = $this->shoppingSessionService->getByUserId($validated['user_id']);
        if ($success) {
            $json = Response::json([
                'success' => true,
                'message' => 'Successfully create cart',
                'data' => $validated
            ], 200);
        } else {
            $json = Response::json([
                'success' => false,
                'message' => 'Failed to create cart',
                'data' => $validated
            ], 400);
        }
        return $json;
    }
    public function store(LoginRequest $request)
    {
        $request->checkThrottle();
        $success = $this->userService->login($request->validated(), $request->throttleKey());
        if ($success) {
            // Add Shopping Session to Database
            $requestCart = Request::create(route('login'), 'POST', [
                'user_id' => Auth::user()->id,
                'total' => 0
            ]);
            $response = $this->createCart($requestCart);
            $successCart = $response->getData(true);
            if ($successCart['success'] == true) {
                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->back()->withErrors(['email' => 'Unexpected Error'])->onlyInput();
            }
        } else {
            return redirect()->back()->withErrors(['email' => trans('auth.failed')])->onlyInput();
        }
    }
    public function destroy(Request $request)
    {
        // Remove Shopping Session from Database
        if (Auth::check()) {
            $this->shoppingSessionService->deleteByUserId(Auth::user()->id);
        }
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(RouteServiceProvider::HOME);
    }
}
