<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
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

Route::middleware('disable')->group(function () {
    Route::get('catalogue', [ProductController::class, 'index'])->name('app.catalogue.page');
    Route::get('aboutus', [AppController::class, 'aboutus'])->name('app.aboutus.page');

    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'index'])->name('app.login.page');
        Route::post('login', [LoginController::class, 'login'])->name('app.login.login');

        Route::get('register', [RegisterController::class, 'index'])->name('app.register.page');
        Route::post('register', [RegisterController::class, 'register'])->name('app.register.register');
    });

    Route::middleware('auth')->group(function () {
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
    Route::middleware('enable')->middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'adminHome'])->name('admin.dashboard');
        Route::get('/admin/invoice', [AdminController::class, 'adminInvoice'])->name('admin.invoice');
        Route::post('/admin', [AdminController::class, 'editData'])->name('adm.edit');
        Route::post('/admin/delete', [AdminController::class, 'deleteData'])->name('adm.delete');
    });
});
