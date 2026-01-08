<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer', 'orderItems.menuItem')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Search by order number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('table_number', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        return view('cashier.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.menuItem', 'payments', 'cashier']);
        return view('cashier.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // Only allow editing of pending orders
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be edited');
        }

        $categories = Category::with(['menuItems' => function($query) {
            $query->where('is_active', true);
        }])->where('is_active', true)->get();

        return view('cashier.orders.edit', compact('order', 'categories'));
    }

    public function update(Request $request, Order $order)
    {
        // Only allow updating of pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be edited'
            ], 400);
        }

        $validated = $request->validate([
            'order_type' => 'required|in:dine_in,takeaway',
            'table_number' => 'nullable|string|max:20',
            'pax' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|json'
        ]);

        try {
            DB::beginTransaction();

            // Update order details
            $order->update([
                'order_type' => $validated['order_type'],
                'table_number' => $validated['order_type'] === 'dine_in' ? $validated['table_number'] : null,
                'pax' => $validated['pax'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes']
            ]);

            // Process items
            $items = json_decode($validated['items'], true);
            $subtotal = 0;

            // Clear existing items
            $order->orderItems()->delete();

            // Add new items
            foreach ($items as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->item_name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $menuItem->price,
                    'total_price' => $menuItem->price * $item['quantity']
                ]);

                $subtotal += $orderItem->total_price;
            }

            // Recalculate totals
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'redirect' => route('cashier.orders.show', $order)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Order $order)
    {
        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be deleted');
        }

        try {
            DB::beginTransaction();

            // Restore inventory if any items were deducted
            foreach ($order->orderItems as $item) {
                $menuItem = $item->menuItem;
                if ($menuItem && $menuItem->track_inventory) {
                    $menuItem->increment('stock_quantity', $item->quantity);
                }
            }

            // Delete payments if any
            $order->payments()->delete();
            
            // Delete order items
            $order->orderItems()->delete();
            
            // Delete order
            $order->delete();

            DB::commit();

            return back()->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function processPayment(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Order is already paid');
        }

        return view('cashier.orders.payment', compact('order'));
    }

   public function submitPayment(Request $request, Order $order)
{
    $validated = $request->validate([
        'payment_method' => 'required|in:cash,card,gcash,maya,bank_transfer',
        'amount' => 'required|numeric|min:0', // ADD THIS
        'amount_paid' => 'required|numeric|min:' . $order->total_amount,
        'change_amount' => 'nullable|numeric|min:0',
        'reference_number' => 'nullable|string|max:50',
        'notes' => 'nullable|string|max:500'
    ]);

    try {
        DB::beginTransaction();

        // Create payment record
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'], // Use from form
            'amount_paid' => $validated['amount_paid'],
            'change_amount' => $validated['change_amount'] ?? ($validated['amount_paid'] - $validated['amount']),
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
            'status' => 'completed',
            'cashier_id' => auth()->id()
        ]);

        // Update order status
        $order->update([
            'payment_status' => 'paid',
            'status' => $order->status === 'pending' ? 'preparing' : $order->status
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully!',
            'receipt_url' => route('cashier.orders.receipt', $payment->id)
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to process payment: ' . $e->getMessage()
        ], 500);
    }
}
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled'
        ]);

        // Validate status transitions
        $allowedTransitions = [
            'pending' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['completed'],
            'completed' => [],
            'cancelled' => []
        ];

        if (!in_array($validated['status'], $allowedTransitions[$order->status])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status transition from ' . $order->status . ' to ' . $validated['status']
            ], 400);
        }

        // Check if order is paid before marking as ready/completed
        if (in_array($validated['status'], ['ready', 'completed']) && $order->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order must be paid before marking as ' . $validated['status']
            ], 400);
        }

        try {
            $order->update(['status' => $validated['status']]);

            // If cancelling, restore inventory
            if ($validated['status'] === 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $menuItem = $item->menuItem;
                    if ($menuItem && $menuItem->track_inventory) {
                        $menuItem->increment('stock_quantity', $item->quantity);
                    }
                }

                // Refund payment if paid
                if ($order->payment_status === 'paid') {
                    $order->update(['payment_status' => 'refunded']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order status updated to ' . $validated['status'],
                'new_status' => $validated['status']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printReceipt(Payment $payment)
    {
        $payment->load('order.orderItems');
        return view('cashier.orders.receipt', compact('payment'));
    }

    public function printOrder(Order $order)
    {
        $order->load('orderItems.menuItem', 'payments');
        return view('cashier.orders.print', compact('order'));
    }

    public function todaysOrders()
    {
        $orders = Order::with('customer', 'orderItems')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('cashier.orders.today', compact('orders'));
    }

    public function unpaidOrders()
    {
        $orders = Order::with('customer', 'orderItems')
            ->where('payment_status', 'unpaid')
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('cashier.orders.unpaid', compact('orders'));
    }

    public function preparingOrders()
    {
        $orders = Order::with('customer', 'orderItems')
            ->where('status', 'preparing')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('cashier.orders.preparing', compact('orders'));
    }

    public function readyOrders()
    {
        $orders = Order::with('customer', 'orderItems')
            ->where('status', 'ready')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('cashier.orders.ready', compact('orders'));
    }

    public function addItem(Request $request, Order $order)
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Only allow adding to pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Can only add items to pending orders'
            ], 400);
        }

        try {
            $menuItem = MenuItem::find($validated['menu_item_id']);

            // Check inventory
            if ($menuItem->track_inventory && $menuItem->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock for ' . $menuItem->item_name
                ], 400);
            }

            // Add item to order
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'item_name' => $menuItem->item_name,
                'quantity' => $validated['quantity'],
                'unit_price' => $menuItem->price,
                'total_price' => $menuItem->price * $validated['quantity']
            ]);

            // Update inventory
            if ($menuItem->track_inventory) {
                $menuItem->decrement('stock_quantity', $validated['quantity']);
            }

            // Recalculate order totals
            $order->refresh();
            $subtotal = $order->orderItems->sum('total_price');
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
                'order_item' => $orderItem,
                'new_totals' => [
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeItem(OrderItem $orderItem)
    {
        // Only allow removal from pending orders
        if ($orderItem->order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Can only remove items from pending orders'
            ], 400);
        }

        try {
            $order = $orderItem->order;
            
            // Restore inventory
            $menuItem = $orderItem->menuItem;
            if ($menuItem && $menuItem->track_inventory) {
                $menuItem->increment('stock_quantity', $orderItem->quantity);
            }

            // Remove item
            $orderItem->delete();

            // Recalculate order totals
            $order->refresh();
            $subtotal = $order->orderItems->sum('total_price');
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item removed successfully',
                'new_totals' => [
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item: ' . $e->getMessage()
            ], 500);
        }
    }
}