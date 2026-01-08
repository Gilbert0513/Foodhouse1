<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with(['menuItems' => function($query) {
            $query->where('status', 'available')->orderBy('item_name');
        }])->orderBy('name')->get();

        return view('customer.menu', compact('categories'));
    }

    // Cart Methods
    public function cart()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        // Calculate total
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('customer.cart', compact('cart', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $menuItem = MenuItem::findOrFail($request->menu_item_id);
        
        // Check stock
        if ($menuItem->track_inventory && $menuItem->stock_quantity < $request->quantity) {
            return redirect()->back()
                ->with('error', "Sorry, only {$menuItem->stock_quantity} {$menuItem->item_name} available.");
        }
        
        $cart = Session::get('cart', []);
        
        // Check if item already in cart
        if (isset($cart[$menuItem->id])) {
            $cart[$menuItem->id]['quantity'] += $request->quantity;
        } else {
            $cart[$menuItem->id] = [
                'id' => $menuItem->id,
                'name' => $menuItem->item_name,
                'price' => $menuItem->price,
                'quantity' => $request->quantity,
                'image' => $menuItem->image_url,
            ];
        }
        
        Session::put('cart', $cart);
        
        return redirect()->route('customer.cart')
            ->with('success', 'Item added to cart!');
    }

    public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
            
            return redirect()->route('customer.cart')
                ->with('success', 'Item removed from cart!');
        }
        
        return redirect()->route('customer.cart')
            ->with('error', 'Item not found in cart!');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);
        
        $cart = Session::get('cart', []);
        
        foreach ($request->items as $item) {
            $menuItemId = $item['id'];
            $quantity = $item['quantity'];
            
            if ($quantity == 0) {
                // Remove item if quantity is 0
                if (isset($cart[$menuItemId])) {
                    unset($cart[$menuItemId]);
                }
            } else {
                // Update quantity
                if (isset($cart[$menuItemId])) {
                    $menuItem = MenuItem::find($menuItemId);
                    
                    // Check stock
                    if ($menuItem->track_inventory && $menuItem->stock_quantity < $quantity) {
                        return redirect()->back()
                            ->with('error', "Sorry, only {$menuItem->stock_quantity} {$menuItem->item_name} available.");
                    }
                    
                    $cart[$menuItemId]['quantity'] = $quantity;
                }
            }
        }
        
        Session::put('cart', $cart);
        
        return redirect()->route('customer.cart')
            ->with('success', 'Cart updated!');
    }

    // Place Order Method
    public function placeOrder(Request $request)
    {
        try {
            $request->validate([
                'order_type' => 'required|in:dine_in,takeaway',
                'table_number' => 'required_if:order_type,dine_in',
                'pax' => 'required|integer|min:1',
                'items' => 'required|array|min:1',
                'items.*.menu_item_id' => 'required|exists:menu_items,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            // Check stock availability and calculate totals
            $subtotal = 0;
            $orderItems = [];
            
            foreach ($request->items as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                
                // Check inventory
                if ($menuItem->track_inventory && $menuItem->stock_quantity < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "{$menuItem->item_name} is out of stock. Only {$menuItem->stock_quantity} available."
                    ], 400);
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

            // Calculate totals
            $tax = $subtotal * 0.12;
            $grandTotal = $subtotal + $tax;
            
            // Generate order number
            $orderCount = Order::whereDate('created_at', today())->count() + 1;
            $orderNumber = 'CUST-ORD-' . date('Ymd') . '-' . str_pad($orderCount, 4, '0', STR_PAD_LEFT);

            // Create order with ALL required fields
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => Auth::id(),
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
            ]);

            // Add order items
            foreach ($orderItems as $itemData) {
                $order->items()->create($itemData);
                
                // Update stock if tracking inventory
                $menuItem = MenuItem::find($itemData['menu_item_id']);
                if ($menuItem->track_inventory) {
                    $menuItem->decrement('stock_quantity', $itemData['quantity']);
                }
            }

            // Clear session cart if using session cart
            Session::forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_number' => $orderNumber,
                'order_id' => $order->id,
                'redirect' => route('customer.orders.show', $order)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('customer.menu')
                ->with('error', 'Your cart is empty!');
        }
        
        return view('customer.checkout', compact('cart'));
    }

    public function clearCart()
    {
        Session::forget('cart');
        
        return redirect()->route('customer.cart')
            ->with('success', 'Cart cleared!');
    }

    // Order Methods - FIXED: Changed from orderDetails to items
    public function myOrders()
    {
        $orders = Order::with(['items.menuItem', 'payments'])
            ->where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Check if order belongs to current customer
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.menuItem', 'payments']);
        return view('customer.orders.show', compact('order'));
    }

    public function cancelOrder(Request $request, Order $order)
    {
        // Check if order belongs to current customer
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'Order cannot be cancelled. It is already being processed.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore stock if inventory tracking
        foreach ($order->items as $item) {
            $menuItem = $item->menuItem;
            if ($menuItem && $menuItem->track_inventory) {
                $menuItem->increment('stock_quantity', $item->quantity);
            }
        }

        return redirect()->route('customer.orders.index')
            ->with('success', 'Order cancelled successfully!');
    }
    
    // Helper method for cart quantity calculation
    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }
}