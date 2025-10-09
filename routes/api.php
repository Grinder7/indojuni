<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingController;
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

Route::get("ping", function () {
    return response()->json([
        "status" => 200,
        "message" => "pong",
        "data" => null
    ]);
});

Route::prefix("v1")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::middleware('guest:sanctum')->group(function () {
            Route::post("login", [LoginController::class, "login"])->name("api.v1.auth.login");
        });
        Route::middleware('auth:sanctum')->group(function () {
            Route::post("logout", [LoginController::class, "logout"])->name("api.v1.auth.logout");
            Route::get("user", function (Request $request) {
                return response()->json([
                    "status" => 200,
                    "message" => "User retrieved successfully",
                    "data" => $request->user()
                ]);
            })->name("api.v1.auth.user");
        });
    });

    Route::prefix("product")->group(function () {
        Route::get("all", [ProductController::class, "getProducts"])->name("api.v1.product.all");
        Route::post("detail", [ProductController::class, "getProductById"])->name("api.v1.product.detail");
        Route::post("search-similar-name", [ProductController::class, "searchSimilarProductByName"])->name("api.v1.product.search.similar");
        Route::post("search-contain-name", [ProductController::class, "searchContainProductByName"])->name("api.v1.product.search.contain");
    });

    Route::prefix("cart")->middleware('auth:sanctum')->group(function () {
        Route::get("current", [ShoppingController::class, "getUserCartItems"])->name("api.v1.cart.current");
        Route::post("modify", [ShoppingController::class, "modifyShoppingCart"])->name("api.v1.cart.modify");
        Route::post("add", [ShoppingController::class, "addShoppingCart"])->name("api.v1.cart.add");
    });

    Route::prefix("checkout")->middleware('auth:sanctum')->group(function () {
        Route::post("", [CheckoutController::class, "checkout"])->name("api.v1.checkout");
    });

    Route::prefix("invoice")->middleware('auth:sanctum')->group(function () {
        Route::get("", [InvoiceController::class, "index"])->name("api.v1.invoice.list");
        Route::get("{id}", [InvoiceController::class, "invoice"])->name("api.v1.invoice.detail");
    });

    Route::prefix("query")->middleware("secretkey")->group(function () {
        Route::post("", [\App\Http\Controllers\QueryController::class, "query"])->name("api.v1.query");
    });
});
