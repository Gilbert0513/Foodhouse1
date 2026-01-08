<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;

// Cashier Controllers
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\POSController as CashierPOSController;

// Customer Controllers
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| PASSWORD RESET ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::resource('menu', AdminMenuController::class);
    Route::post('/menu/{menuItem}/update-stock', [AdminMenuController::class, 'updateStock'])->name('menu.update-stock');

    Route::resource('categories', AdminCategoryController::class);

    Route::get('/reports/sales', [AdminReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/inventory', [AdminReportController::class, 'inventoryReport'])->name('reports.inventory');
    Route::get('/reports/customers', [AdminReportController::class, 'customerReport'])->name('reports.customers');
    Route::get('/reports/daily', [AdminReportController::class, 'dailyReport'])->name('reports.daily');

    Route::resource('inventory', AdminInventoryController::class);
    Route::get('inventory/{ingredient}/adjust-stock', [AdminInventoryController::class, 'showAdjustStock'])->name('inventory.adjust-stock');
    Route::post('inventory/{ingredient}/adjust-stock', [AdminInventoryController::class, 'adjustStock']);
    Route::get('inventory/{ingredient}/menu-items', [AdminInventoryController::class, 'menuItems'])->name('inventory.menu-items');
    Route::get('inventory-logs', [AdminInventoryController::class, 'logs'])->name('inventory.logs');

    Route::resource('orders', AdminOrderController::class);
    Route::get('/orders/{order}/print-kitchen', [AdminOrderController::class, 'printKitchenTicket'])->name('orders.print-kitchen');
    Route::get('/orders/{order}/print-receipt', [AdminOrderController::class, 'printReceipt'])->name('orders.print-receipt');

    Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/complete', [AdminOrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order}/serve', [AdminOrderController::class, 'serve'])->name('orders.serve');
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES  ✅ UPDATED
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('cashier')->name('cashier.')->group(function () {

    Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('dashboard');

    // 🔥 ADDED: Active Orders (FIXES ERROR)
    Route::get('/orders/active', [CashierDashboardController::class, 'activeOrders'])
        ->name('orders.active');
    // Today's orders
Route::get('/orders/today', [CashierDashboardController::class, 'todayOrders'])
    ->name('orders.today');
Route::get('/orders/preparing', [CashierDashboardController::class, 'preparingOrders'])->name('orders.preparing');
Route::get('/orders/ready', [CashierDashboardController::class, 'readyOrders'])->name('orders.ready'); // ✅ NEW
Route::get('/orders/{order}/pending-payment', [CashierDashboardController::class, 'showOrder'])
    ->name('orders.pending-payment');
    Route::get('/orders/{order}/print-receipt', [CashierDashboardController::class, 'showPrintReceipt'])
    ->name('orders.print-receipt');
    Route::get('/orders/{order}/receipt', [CashierDashboardController::class, 'showReceipt'])
    ->name('orders.receipt');
    Route::get('/orders/payment', [CashierDashboardController::class, 'paymentOrders'])
    ->name('orders.payment');
    // Existing: Unpaid Orders
    Route::get('/orders/unpaid', [CashierDashboardController::class, 'unpaidOrders'])
        ->name('orders.unpaid');

    // POS
    Route::get('/pos', [CashierPOSController::class, 'index'])->name('pos');
    Route::post('/pos/place-order', [CashierPOSController::class, 'placeOrder'])->name('pos.place-order');
    Route::get('/pos/menu-items', [CashierPOSController::class, 'getMenuItems'])->name('pos.menu-items');

    // Orders
    Route::get('/orders', [CashierDashboardController::class, 'allOrders'])->name('orders.index');
    Route::get('/orders/{order}', [CashierDashboardController::class, 'showOrder'])->name('orders.show');

    Route::patch('/orders/{order}/update-status', [CashierPOSController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/process-payment', [CashierPOSController::class, 'processPayment'])->name('orders.process-payment');
    Route::post('/orders/{order}/add-note', [CashierPOSController::class, 'addNote'])->name('orders.add-note');
    Route::post('/orders/{order}/cancel', [CashierPOSController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{order}/complete', [CashierPOSController::class, 'completeOrder'])->name('orders.complete');
    Route::post('/orders/{order}/serve', [CashierPOSController::class, 'serveOrder'])->name('orders.serve');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {

    Route::get('/dashboard', fn() => view('customer.dashboard'))->name('dashboard');
    Route::get('/profile', fn() => view('customer.profile'))->name('profile');
    Route::post('/profile/update', [CustomerMenuController::class, 'updateProfile'])->name('profile.update');

    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu');
    Route::get('/menu/category/{category}', [CustomerMenuController::class, 'byCategory'])->name('menu.category');
    Route::get('/menu/item/{menuItem}', [CustomerMenuController::class, 'showItem'])->name('menu.item');

    Route::get('/cart', [CustomerMenuController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [CustomerMenuController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CustomerMenuController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update', [CustomerMenuController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart/checkout', [CustomerMenuController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/clear', [CustomerMenuController::class, 'clearCart'])->name('cart.clear');

    Route::get('/orders', [CustomerMenuController::class, 'myOrders'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerMenuController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders', [CustomerMenuController::class, 'placeOrder'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [CustomerMenuController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{order}/rate', [CustomerMenuController::class, 'rateOrder'])->name('orders.rate');

    Route::post('/favorites/add/{menuItem}', [CustomerMenuController::class, 'addToFavorites'])->name('favorites.add');
    Route::delete('/favorites/remove/{menuItem}', [CustomerMenuController::class, 'removeFromFavorites'])->name('favorites.remove');
    Route::get('/favorites', [CustomerMenuController::class, 'favorites'])->name('favorites');

    Route::get('/contact', fn() => view('customer.contact'))->name('contact');
    Route::get('/help', fn() => view('customer.help'))->name('help');
    Route::post('/contact/send', [CustomerMenuController::class, 'sendContact'])->name('contact.send');
});



// TEST ROUTES - Add these to verify everything works
Route::get('/test-login', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'Routes are working',
        'auth_status' => auth()->check() ? 'Logged in' : 'Not logged in',
        'user' => auth()->user()
    ]);
});

Route::get('/create-test-users', function() {
    // Create demo users if they don't exist
    $users = [
        [
            'name' => 'Admin User',
            'email' => 'admin@foodhouse.com',
            'password' => bcrypt('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Cashier User',
            'email' => 'cashier@foodhouse.com', 
            'password' => bcrypt('cashier123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Customer User',
            'email' => 'customer@foodhouse.com',
            'password' => bcrypt('customer123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];
    
    foreach ($users as $user) {
        \App\Models\User::create($user);
    }
    
    return 'Demo users created!';
});

// Debug route to check all cashier routes
Route::get('/debug-cashier-routes', function() {
    $routes = collect(Route::getRoutes()->getRoutesByName())
        ->filter(function ($route, $name) {
            return str_contains($name, 'cashier.');
        })
        ->map(function ($route, $name) {
            return [
                'name' => $name,
                'uri' => $route->uri,
                'methods' => $route->methods,
                'action' => $route->action['controller'] ?? 'Closure',
            ];
        })
        ->values();
    
    return response()->json($routes);
});

// TEST ROUTES - Add these to verify everything works
Route::get('/test-login', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'Routes are working',
        'auth_status' => auth()->check() ? 'Logged in' : 'Not logged in',
        'user' => auth()->user()
    ]);
});

Route::get('/create-test-users', function() {
    // Create demo users if they don't exist
    $users = [
        [
            'name' => 'Admin User',
            'email' => 'admin@foodhouse.com',
            'password' => bcrypt('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Cashier User',
            'email' => 'cashier@foodhouse.com', 
            'password' => bcrypt('cashier123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Customer User',
            'email' => 'customer@foodhouse.com',
            'password' => bcrypt('customer123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];
    
    foreach ($users as $user) {
        \App\Models\User::create($user);
    }
    
    return 'Demo users created!';
});

// Debug route to check all cashier routes
Route::get('/debug-cashier-routes', function() {
    $routes = collect(Route::getRoutes()->getRoutesByName())
        ->filter(function ($route, $name) {
            return str_contains($name, 'cashier.');
        })
        ->map(function ($route, $name) {
            return [
                'name' => $name,
                'uri' => $route->uri,
                'methods' => $route->methods,
                'action' => $route->action['controller'] ?? 'Closure',
            ];
        })
        ->values();
    
    return response()->json($routes);
});