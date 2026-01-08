<?php

use App\Http\Controllers\Cashier\DashboardController;
use App\Http\Controllers\Cashier\POSController;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\PaymentController;
use App\Http\Middleware\CashierMiddleware;

Route::middleware([CashierMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('cashier.dashboard');
    
    // POS System
    Route::get('/pos', [POSController::class, 'index'])->name('cashier.pos');
    Route::post('/pos/place-order', [POSController::class, 'placeOrder'])->name('cashier.pos.place-order');
    Route::post('/orders/{order}/process-payment', [POSController::class, 'processPayment'])->name('cashier.orders.process-payment');
    
    // Orders Management
    Route::get('/orders/active', [POSController::class, 'getActiveOrders'])->name('cashier.orders.active');
    Route::post('/orders/{order}/update-status', [POSController::class, 'updateOrderStatus'])->name('cashier.orders.update-status');
    Route::get('/orders', [OrderController::class, 'index'])->name('cashier.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('cashier.orders.show');
    
    // Customer Management
    Route::get('/customers', [CustomerController::class, 'index'])->name('cashier.customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('cashier.customers.store');
});