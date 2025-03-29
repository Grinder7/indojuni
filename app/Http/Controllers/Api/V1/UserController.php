<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\ShoppingSession;
use App\Modules\ShoppingCart\ShoppingSession\ShoppingSessionService;
use App\Modules\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserService $userService;
    private ShoppingSessionService $shoppingSessionService;
    public function __construct(UserService $userService, ShoppingSessionService $shoppingSessionService)
    {
        $this->userService = $userService;
        $this->shoppingSessionService = $shoppingSessionService;
    }

    private function errorResponse($message = 'Failed to login'): JsonResponse
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
            'data' => null
        ], 400);
    }

    private function successResponse($message = "Success", $data = null): JsonResponse
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    private function createCart(Request $request): ShoppingSession
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total' => 'integer'
        ]);
        $shoppingSessionData = $this->shoppingSessionService->getByUserId($validated['user_id']);
        if ($shoppingSessionData) {
            return $shoppingSessionData;
        }
        return $this->shoppingSessionService->create($validated);
    }

    public function postAuthLogin(LoginRequest $request): JsonResponse
    {
        $request->checkThrottle();
        $loginSuccess = $this->userService->login($request->validated(), $request->throttleKey());
        if (!$loginSuccess) {
            return $this->errorResponse('credentials not match');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $shoppingSessionData = $this->shoppingSessionService->getByUserId($user->id);
        if (!$shoppingSessionData) {
            $requestCart = Request::create("", 'POST', [
                'user_id' => $user->id,
                'total' => 0
            ]);
            // Add Shopping Session to Database
            $shoppingSessionData = $this->createCart($requestCart);
            if (!$shoppingSessionData) {
                return $this->errorResponse("failed to create cart");
            }
        }

        $accessToken = $user->createToken('auth_token', ['*'], now()->addMonth(1))->plainTextToken;

        return $this->successResponse('Successfully login', [
            "user" => $user,
            "shopping_session" => $shoppingSessionData,
            "access_token" => $accessToken
        ]);
    }

    public function postAuthLogout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse('Successfully logout');
    }

    public function getAuthCheck(): JsonResponse
    {
        return $this->successResponse('User is authenticated', Auth::user());
    }
}
