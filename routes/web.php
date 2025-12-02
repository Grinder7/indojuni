<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShoppingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('', [AppController::class, 'home'])->name('app.home.page');

Route::middleware('enable')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'index'])->name('app.login.page');
        Route::post('login', [LoginController::class, 'login'])->name('app.login.login');
    });
    Route::get('catalogue', [ProductController::class, 'index'])->name('app.catalogue.page');
    Route::get('aboutus', [AppController::class, 'aboutus'])->name('app.aboutus.page');
    Route::middleware('auth')->group(function () {
        Route::get('profile', [ProfileController::class, 'index'])->name('app.profile.page');
        Route::post('profile', [ProfileController::class, 'store'])->name('app.profile.store');
        Route::get('checkout', [CheckoutController::class, 'index'])->name('app.checkout.page');
        Route::get('invoice', [InvoiceController::class, 'index'])->name('app.invoice.page');
        Route::get('invoice/{id}', [InvoiceController::class, 'invoice'])->name('app.invoice.invoice');
        Route::get('logout', [LoginController::class, 'logout'])->name('app.logout.logout');

        Route::prefix('cart')->group(function () {
            Route::post('add', [ShoppingController::class, 'addShoppingCart'])->name('app.cart.add');
            Route::post('modify', [ShoppingController::class, 'modifyShoppingCart'])->name('app.cart.modify');
        });

        Route::post('checkout', [CheckoutController::class, 'checkout'])->name('app.checkout.checkout');
    });
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('adm.dashboard.page');
        Route::post('/admin', [AdminController::class, 'modify'])->name('adm.edit');
        Route::post('/admin/delete', [AdminController::class, 'deleteData'])->name('adm.delete');
    });
});

Route::middleware('disable')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [RegisterController::class, 'index'])->name('app.register.page');
        Route::post('register', [RegisterController::class, 'register'])->name('app.register.register');
    });
});

Route::get('/blank', function () {
    return view('pages.blank');
});

Route::prefix('chatbot')->group(function () {
    Route::post('get-chats', [ChatbotController::class, 'getChats'])->name('chat.get');
    Route::put('clear-chats', [ChatbotController::class, 'clearChats'])->name('chat.clear');
    Route::post('init-chat', [ChatbotController::class, 'initChat'])->name('chat.init');
    Route::post('send-message', [ChatbotController::class, 'sendMessage'])->name('chat.send');
});
