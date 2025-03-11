<?php

use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("ping", function () {
    return response()->json([
        "status" => 200,
        "message" => "pong",
        "data" => null
    ]);
});

Route::prefix("v1")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post("login", [UserController::class, "postAuthLogin"]);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get("check", [UserController::class, "getAuthCheck"]);
            Route::post("logout", [UserController::class, "postAuthLogout"]);
        });
    });

    Route::prefix("product")->group(function () {
        Route::get("all", [ProductController::class, "getAllProduct"]);

        // Route::middleware('auth:sanctum')->group(function () {
        //     Route::get("all", [ProductController::class, "getAllProduct"]);
        // });
    });

    Route::prefix("cart")->middleware('auth:sanctum')->group(function () {
        Route::get("current", [CartController::class, "getUserCartItems"]);
        Route::post("add", [CartController::class, "postAddCartItem"]);
        Route::post("remove", [CartController::class, "postRemoveCartItem"]);
    });
});
