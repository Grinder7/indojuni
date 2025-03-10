<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Successfully login',
    //     'data' => ""
    // ], 200);
});

Route::prefix("v1")->group(function () {
    // authentication
    Route::prefix("auth")->group(function () {

        Route::post("login", [UserController::class, "postAuthLogin"]);

        Route::middleware('auth:sanctum')->group(function () {
            Route::prefix("auth")->group(function () {
                Route::post("logout", [UserController::class, "postAuthLogout"]);
            });
        });
    });

    // Route::prefix("product")->group(function () {
    //     Route::get("all", [ProductController::class, "getAllProduct"]);
    // });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix("auth")->group(function () {
            Route::post("logout", [UserController::class, "postAuthLogout"]);
        });

        Route::prefix("product")->group(function () {
            Route::get("all", [ProductController::class, "getAllProduct"]);
        });
    });
});
