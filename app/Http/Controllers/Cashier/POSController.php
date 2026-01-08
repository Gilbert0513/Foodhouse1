<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        // REMOVE the is_available filter since your table doesn't have it
        $categories = Category::with(['menuItems' => function($query) {
            // Just order by item_name, no is_available filter
            $query->orderBy('item_name', 'asc');
        }])->orderBy('name')->get();

        $tables = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10'];
        
        return view('cashier.pos.index', compact('categories', 'tables'));
    }

    public function pendingPayment(Order $order)
    {
        return view('cashier.orders.pending-payment', compact('order'));
    }

    public function showPayment(Order $order)
    {
        return view('cashier.payment', compact('order'));
    }

    public function getReceipt(Order $order)
    {
        return view('cashier.receipt', compact('order'));
    }

    public function printReceipt(Order $order)
    {
        return view('cashier.print-receipt', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:preparing,ready,served,cancelled'
        ]);

        $order->update(['status' => $request->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated'
        ]);
    }

    public function placeOrder(Request $request)
    {
        \Log::info('=== PLACE ORDER REQUEST ===');
        \Log::info('Request data:', $request->all());
        \Log::info('User ID:', [auth()->id()]);
        \Log::info('CSRF Token:', [csrf_token()]);
        
        try {
            DB::beginTransaction();
            
            \Log::info('Starting order creation...');
            
            // Generate order number
            $orderCount = \App\Models\Order::whereDate('created_at', today())->count() + 1;
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad($orderCount, 4, '0', STR_PAD_LEFT);
            
            \Log::info('Order number generated:', [$orderNumber]);
            
            // DECODE THE JSON STRING - IMPORTANT!
            $items = $request->input('items');
            \Log::info('Raw items input:', ['type' => gettype($items), 'value' => $items]);
            
            if (is_string($items)) {
                $items = json_decode($items, true);
                \Log::info('Items after JSON decode:', $items);
            }
            
            // Validate that items is an array
            if (!is_array($items) || empty($items)) {
                throw new \Exception("Invalid items data. Expected array, got: " . gettype($items));
            }
            
            $subtotal = 0;
            $orderItems = [];
            
            foreach ($items as $item) {
                \Log::info('Processing item:', $item);
                $menuItem = \App\Models\MenuItem::find($item['menu_item_id']);
                
                if (!$menuItem) {
                    throw new \Exception("Menu item not found with ID: " . $item['menu_item_id']);
                }
                
                $itemTotal = $menuItem->price * $item['quantity'];
                $subtotal += $itemTotal;
                
                $orderItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                    'total' => $itemTotal,
                ];
            }
            
            $tax = $subtotal * 0.12;
            $grandTotal = $subtotal + $tax;
            
            \Log::info('Calculated totals:', [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'grand_total' => $grandTotal
            ]);
            
            // Create order data
            $orderData = [
                'order_number' => $orderNumber,
                'customer_id' => null,
                'cashier_id' => auth()->id(),
                'order_type' => $request->order_type,
                'table_number' => $request->order_type === 'dine_in' ? $request->table_number : null,
                'pax' => $request->pax,
                'special_instructions' => $request->special_instructions ?? null,
                'total_amount' => $subtotal,
                'tax_amount' => $tax,
                'discount_amount' => 0,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'order_time' => now(),
            ];
            
            \Log::info('Order data to create:', $orderData);
            
            // Create the order
            $order = \App\Models\Order::create($orderData);
            
            \Log::info('Order created successfully:', ['id' => $order->id]);
            
            // Add items
            foreach ($orderItems as $itemData) {
                $order->items()->create($itemData);
            }
            
            \Log::info('Order items added:', ['count' => count($orderItems)]);
            
            DB::commit();
            
            \Log::info('=== ORDER CREATION SUCCESS ===');
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!',
                'order' => $order,
                'order_id' => $order->id,
                'redirect' => route('cashier.orders.pending-payment', $order)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('=== ORDER CREATION ERROR ===');
            \Log::error('Error message: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ', $request->all());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,credit_card,debit_card,gcash,paymaya,bank_transfer',
            'amount' => 'required|numeric|min:' . $order->grand_total,
            'change_amount' => 'nullable|numeric|min:0',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Calculate change
            $changeAmount = 0;
            if ($request->amount > $order->grand_total) {
                $changeAmount = $request->amount - $order->grand_total;
            }

            // Create payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'cashier_id' => auth()->id(),
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'change_amount' => $changeAmount,
                'transaction_id' => 'PAY' . now()->format('YmdHis') . rand(1000, 9999),
                'status' => 'completed',
                'payment_reference' => $request->payment_reference,
            ]);

            // Update order
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'order_id' => $order->id,
                'change_amount' => $changeAmount,
                'receipt_url' => route('cashier.orders.receipt', $order)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMenuItems(Request $request)
    {
        // Use item_name for ordering
        $menuItems = MenuItem::where('status', 'active')
            ->when($request->category_id, function($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->has('search'), function($query) use ($request) {
                return $query->where('item_name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('item_name', 'asc')  // Changed from name to item_name
            ->get(['id', 'item_name', 'price', 'image_url', 'description', 'category_id', 'inventory_status', 'track_inventory', 'preparation_time', 'stock_quantity']);
        
        // Transform data
        $transformedItems = $menuItems->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->item_name,  // Map item_name to name for frontend
                'price' => $item->price,
                'image' => $item->image_url,  // Map image_url to image for frontend
                'description' => $item->description,
                'category_id' => $item->category_id,
                'inventory_status' => $item->inventory_status ?? 'available',
                'track_inventory' => $item->track_inventory ?? false,
                'preparation_time' => $item->preparation_time ?? 15,
                'stock_quantity' => $item->stock_quantity ?? 0,
                'is_available' => true, // Since we filtered by status='active'
            ];
        });
        
        return response()->json($transformedItems);
    }

    public function createCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'address' => 'nullable|string',
        ]);

        $customer = User::create([
            'name' => $request->name,
            'email' => $request->email ?? null,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
            'password' => bcrypt('password123'),
        ]);

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

    public function checkAvailability($menuItemId, $quantity = 1)
    {
        $menuItem = MenuItem::findOrFail($menuItemId);
        
        if (!$menuItem->track_inventory) {
            return response()->json([
                'available' => true,
                'message' => 'Item available'
            ]);
        }

        $available = $menuItem->stock_quantity >= $quantity;
        
        return response()->json([
            'available' => $available,
            'message' => $available ? 'Item available' : 'Insufficient stock',
            'current_stock' => $menuItem->stock_quantity,
            'required' => $quantity
        ]);
    }

    // Order Management Methods

    public function addNote(Request $request, Order $order)
    {
        $request->validate([
            'staff_notes' => 'nullable|string'
        ]);
        
        $order->update(['staff_notes' => $request->staff_notes]);
        
        return back()->with('success', 'Notes saved successfully!');
    }

    public function updateItemStatus(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:order_details,id',
            'status' => 'required|in:pending,preparing,ready,served',
            'order_id' => 'required|exists:orders,id'
        ]);
        
        $orderItem = OrderDetail::find($request->item_id);
        $orderItem->update(['item_status' => $request->status]);
        
        return response()->json(['success' => true]);
    }

    public function completeOrder(Order $order)
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        return back()->with('success', 'Order marked as completed!');
    }

    public function serveOrder(Order $order)
    {
        $order->update([
            'status' => 'served',
            'served_at' => now()
        ]);
        
        return back()->with('success', 'Order marked as served!');
    }

    public function cancelOrder(Request $request, Order $order)
    {
        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason ?? 'No reason provided'
        ]);
        
        return back()->with('success', 'Order cancelled successfully!');
    }

    public function printKitchenTicket(Order $order)
    {
        return view('cashier.orders.print-kitchen', compact('order'));
    }

    public function customers()
    {
        $customers = User::where('role', 'customer')->latest()->paginate(20);
        
        return view('cashier.customers.index', compact('customers'));
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->get('search');
        $customers = User::where('role', 'customer')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);
        
        return view('cashier.customers.index', compact('customers'));
    }

    public function tables()
    {
        $tables = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10'];
        return response()->json($tables);
    }

    public function assignTable(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:10',
            'order_id' => 'required|exists:orders,id'
        ]);
        
        $order = Order::find($request->order_id);
        $order->update(['table_number' => $request->table_number]);
        
        return response()->json(['success' => true]);
    }

    public function quickOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);
        
        // Generate order number
        $orderCount = Order::whereDate('created_at', today())->count() + 1;
        $orderNumber = 'Q-' . date('Ymd') . '-' . str_pad($orderCount, 4, '0', STR_PAD_LEFT);
        
        // Create a quick order
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => auth()->id(),
            'customer_name' => 'Quick Order',
            'order_type' => 'takeaway',
            'total_amount' => 0,
            'grand_total' => 0,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'cashier_id' => auth()->id(),
        ]);
        
        $total = 0;
        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['id']);
            $subtotal = $menuItem->price * $item['quantity'];
            
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $item['quantity'],
                'price' => $menuItem->price,
                'total' => $subtotal
            ]);
            
            $total += $subtotal;
        }
        
        $tax = $total * 0.12;
        $grandTotal = $total + $tax;
        
        $order->update([
            'total_amount' => $total,
            'tax_amount' => $tax,
            'grand_total' => $grandTotal
        ]);
        
        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'redirect' => route('cashier.orders.payment', $order)
        ]);
    }
}