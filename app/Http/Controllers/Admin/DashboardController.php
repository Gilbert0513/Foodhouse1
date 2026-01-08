<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
{
    $today = today();
    $yesterday = today()->subDay();
    $weekStart = now()->startOfWeek();
    
    // Today's sales - using Order model with payment_status = 'paid'
    $totalSalesToday = Order::whereDate('created_at', $today)
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    
    $totalOrdersToday = Order::whereDate('created_at', $today)->count();
    $paidOrdersToday = Order::whereDate('created_at', $today)
        ->where('payment_status', 'paid')
        ->count();
    
    // Yesterday's data for comparison
    $yesterdaySales = Order::whereDate('created_at', $yesterday)
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    
    // Weekly data
    $weeklySales = Order::whereBetween('created_at', [$weekStart, now()])
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    
    $weeklyOrders = Order::whereBetween('created_at', [$weekStart, now()])->count();
    
    // Get dashboard statistics
    $stats = [
        'total_sales_today' => $totalSalesToday,
        'total_orders_today' => $totalOrdersToday,
        'pending_orders' => Order::whereDate('created_at', $today)
            ->where('payment_status', '!=', 'paid')
            ->count(),
        'paid_orders_today' => $paidOrdersToday,
        'yesterday_sales' => $yesterdaySales,
        'low_stock_items' => MenuItem::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->where('status', 'available')
            ->count(),
        'weekly_sales' => $weeklySales,
        'weekly_orders' => $weeklyOrders,
        'active_customers' => User::customers()->active()->count(),
        'total_menu_items' => MenuItem::count(),
    ];
    
    // Calculate averages
    if ($totalOrdersToday > 0) {
        $stats['order_completion_rate'] = number_format(($paidOrdersToday / $totalOrdersToday) * 100, 1);
    } else {
        $stats['order_completion_rate'] = 0;
    }
    
    // Average daily orders (this week)
    $daysThisWeek = now()->dayOfWeekIso; // 1-7 (Monday-Sunday)
    $stats['avg_daily_orders'] = $daysThisWeek > 0 ? round($weeklyOrders / $daysThisWeek) : 0;
    
    // Peak hour calculation
    $peakHour = Order::whereDate('created_at', $today)
        ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
        ->groupBy('hour')
        ->orderByDesc('count')
        ->first();
    
    if ($peakHour && $peakHour->hour) {
        $stats['peak_hour'] = date('g A', strtotime($peakHour->hour . ':00'));
    } else {
        $stats['peak_hour'] = 'N/A';
    }
    
    // Cancelled orders today
    $stats['cancelled_orders'] = Order::whereDate('created_at', $today)
        ->where('status', 'cancelled')
        ->count();
    
    // Recent orders
    $recentOrders = Order::with(['customer', 'cashier'])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    // Top selling items
    $topItems = DB::table('order_details')
        ->join('menu_items', 'order_details.menu_item_id', '=', 'menu_items.id')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->select(
            'menu_items.item_name',
            DB::raw('SUM(order_details.quantity) as total_sold')
        )
        ->where('orders.payment_status', 'paid')
        ->whereBetween('order_details.created_at', [now()->subDays(7), now()])
        ->groupBy('menu_items.id', 'menu_items.item_name')
        ->orderByDesc('total_sold')
        ->limit(5)
        ->get();
    
    return view('admin.dashboard', compact(
        'stats',
        'recentOrders',
        'topItems'
    ));
}
}