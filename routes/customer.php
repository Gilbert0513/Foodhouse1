<?php

use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Middleware\CustomerMiddleware;

Route::middleware([CustomerMiddleware::class])->group(function () {
    // Menu Browsing
    Route::get('/menu', [MenuController::class, 'index'])->name('customer.menu');
    
    // Order Management
    Route::post('/orders', [MenuController::class, 'placeOrder'])->name('customer.orders.store');
    Route::get('/orders', [MenuController::class, 'myOrders'])->name('customer.orders.index');
    Route::get('/orders/{order}', [MenuController::class, 'showOrder'])->name('customer.orders.show');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('customer.cart');
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('customer.cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('customer.cart.remove');
});