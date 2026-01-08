<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $todaySales = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
            
        $todayOrders = Order::whereDate('created_at', $today)->count();
        
        $pendingOrders = Order::where('status', 'pending')->count();
        
        $readyOrders = Order::where('status', 'ready')->count();
        
        return view('cashier.dashboard', compact(
            'todaySales',
            'todayOrders',
            'pendingOrders',
            'readyOrders'
        ));
    }
    
    // Add other methods for different order views
    public function ordersToday()
    {
        $today = Carbon::today();
        $orders = Order::whereDate('created_at', $today)
            ->with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('cashier.orders.today', compact('orders'));
    }
    
    public function unpaidOrders()
    {
        $orders = Order::where('payment_status', 'pending')
            ->orWhere('payment_status', 'partial')
            ->with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('cashier.orders.unpaid', compact('orders'));
    }
    
    public function preparingOrders()
    {
        $orders = Order::where('status', 'preparing')
            ->with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('cashier.orders.preparing', compact('orders'));
    }
    
    public function readyOrders()
    {
        $orders = Order::where('status', 'ready')
            ->with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('cashier.orders.ready', compact('orders'));
    }
    
    public function activeOrders()
    {
        $orders = Order::whereIn('status', ['confirmed', 'preparing', 'ready'])
            ->with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('cashier.orders.active', compact('orders'));
    }
    
    public function allOrders()
    {
        $orders = Order::with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('cashier.orders.index', compact('orders'));
    }
    
    // ADD THIS METHOD - Show individual order
    public function showOrder(Order $order)
    {
        $order->load(['customer', 'items.menuItem', 'payments']);
        
        return view('cashier.orders.show', compact('order'));
    }
    
    // ADD THIS METHOD - Filter orders by status
    public function filterOrders($status)
    {
        $orders = Order::where('status', $status)
            ->with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('cashier.orders.index', compact('orders'));
    }
    
    // ADD THIS METHOD - Search orders
    public function searchOrders(Request $request)
    {
        $search = $request->get('search');
        
        $orders = Order::where('order_number', 'like', "%{$search}%")
            ->orWhere('customer_name', 'like', "%{$search}%")
            ->orWhere('customer_phone', 'like', "%{$search}%")
            ->with(['customer', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('cashier.orders.index', compact('orders'));
    }
}