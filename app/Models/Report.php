<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_type',
        'report_date',
        'data',
        'total_sales',
        'total_orders',
        'total_customers',
        'total_cost',
        'total_profit'
    ];

    protected $casts = [
        'data' => 'array',
        'total_sales' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'report_date' => 'date'
    ];

    public static function generateDailyReport($date = null)
    {
        $date = $date ?? now()->format('Y-m-d');
        
        $orders = Order::whereDate('created_at', $date)
            ->where('payment_status', 'paid')
            ->get();

        $payments = Payment::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        $newCustomers = User::customers()
            ->whereDate('created_at', $date)
            ->count();

        $data = [
            'orders_by_type' => $orders->groupBy('order_type')->map->count(),
            'payments_by_method' => $payments->groupBy('payment_method')->map->sum('amount'),
            'top_items' => self::getTopItems($date),
            'hourly_sales' => self::getHourlySales($date),
        ];

        $report = self::create([
            'report_type' => 'daily',
            'report_date' => $date,
            'data' => $data,
            'total_sales' => $payments->sum('amount'),
            'total_orders' => $orders->count(),
            'total_customers' => $newCustomers,
            'total_cost' => self::calculateCost($orders),
            'total_profit' => $payments->sum('amount') - self::calculateCost($orders),
        ]);

        return $report;
    }

    private static function getTopItems($date)
    {
        return \DB::table('order_details')
            ->join('menu_items', 'order_details.menu_item_id', '=', 'menu_items.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', $date)
            ->where('orders.payment_status', 'paid')
            ->select(
                'menu_items.item_name',
                \DB::raw('SUM(order_details.quantity) as quantity'),
                \DB::raw('SUM(order_details.subtotal) as revenue')
            )
            ->groupBy('menu_items.id', 'menu_items.item_name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private static function getHourlySales($date)
    {
        return Payment::select(
                \DB::raw('HOUR(created_at) as hour'),
                \DB::raw('SUM(amount) as sales'),
                \DB::raw('COUNT(*) as transactions')
            )
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->toArray();
    }

    private static function calculateCost($orders)
    {
        $totalCost = 0;
        
        foreach ($orders as $order) {
            foreach ($order->orderDetails as $detail) {
                $cost = $detail->menuItem->cost ?? 0;
                $totalCost += $cost * $detail->quantity;
            }
        }
        
        return $totalCost;
    }
}