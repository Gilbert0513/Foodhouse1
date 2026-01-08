<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ... your existing methods ...
    
    /**
     * Get Today's Sales Summary for Dashboard
     */
    public function getTodaysSales()
    {
        $today = today();
        
        // Total Sales Amount
        $totalSales = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        
        // Total Orders Today
        $totalOrders = Order::whereDate('created_at', $today)->count();
        
        // Paid Orders Today
        $paidOrders = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->count();
        
        // Pending Orders (unpaid)
        $pendingOrders = Order::whereDate('created_at', $today)
            ->where('payment_status', '!=', 'paid')
            ->count();
        
        // Average Order Value
        $averageOrderValue = $paidOrders > 0 ? $totalSales / $paidOrders : 0;
        
        // Payment Method Breakdown
        $paymentMethods = Payment::select('payment_method', DB::raw('SUM(amount) as total'))
            ->whereDate('created_at', $today)
            ->groupBy('payment_method')
            ->get();
        
        return [
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'paid_orders' => $paidOrders,
            'pending_orders' => $pendingOrders,
            'average_order_value' => $averageOrderValue,
            'payment_methods' => $paymentMethods
        ];
    }
    
    /**
     * Get Weekly Sales Data
     */
    public function getWeeklySales()
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        
        $weeklySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return $weeklySales;
    }
    
    /**
     * Get Monthly Sales Data
     */
    public function getMonthlySales()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        
        $monthlySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'sales_data' => $monthlySales,
            'month_total' => $monthlySales->sum('total_sales'),
            'month_orders' => $monthlySales->sum('order_count')
        ];
    }
    
    /**
     * Get Sales by Payment Method
     */
    public function getSalesByPaymentMethod($period = 'today')
    {
        $query = Payment::select(
            'payment_method',
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as transaction_count')
        );
        
        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        }
        
        return $query->groupBy('payment_method')->get();
    }
    
    /**
     * Get Top Selling Items
     */
    public function getTopSellingItems($limit = 10)
    {
        return OrderDetail::select(
                'menu_item_id',
                'item_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'paid')
                      ->whereDate('created_at', today());
            })
            ->groupBy('menu_item_id', 'item_name')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }
}