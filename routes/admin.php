<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Middleware\AdminMiddleware;

Route::middleware([AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::resource('users', UserController::class)->names('admin.users');
    
    // Menu Management
    Route::resource('menu', MenuController::class)->names('admin.menu');
    Route::post('/menu/{menuItem}/update-stock', [MenuController::class, 'updateStock'])->name('admin.menu.update-stock');
    
    // Reports
    Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('admin.reports.sales');
    Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('admin.reports.inventory');
    
    // Categories
    Route::resource('categories', CategoryController::class)->names('admin.categories');
});