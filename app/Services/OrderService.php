<?php

namespace App\Services;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Check stock availability
            foreach ($data['items'] as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                if (!$menuItem->isInStock($item['quantity'])) {
                    throw new \Exception("Insufficient stock for: {$menuItem->item_name}");
                }
            }

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $data['customer_id'],
                'cashier_id' => $data['cashier_id'] ?? null,
                'order_type' => $data['order_type'],
                'table_number' => $data['table_number'] ?? null,
                'pax' => $data['pax'] ?? 1,
                'special_instructions' => $data['special_instructions'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'total_amount' => 0,
                'grand_total' => 0,
            ]);

            // Add order details and calculate totals
            $this->addOrderDetails($order, $data['items']);

            return $order;
        });
    }

    private function addOrderDetails(Order $order, array $items)
    {
        $totalAmount = 0;

        foreach ($items as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            $subtotal = $menuItem->price * $item['quantity'];
            
            $order->orderDetails()->create([
                'menu_item_id' => $menuItem->id,
                'quantity' => $item['quantity'],
                'unit_price' => $menuItem->price,
                'subtotal' => $subtotal,
                'special_instructions' => $item['special_instructions'] ?? null,
            ]);

            $totalAmount += $subtotal;
        }

        // Calculate totals
        $tax = $totalAmount * 0.12; // 12% VAT
        $grandTotal = $totalAmount + $tax;

        $order->update([
            'total_amount' => $totalAmount,
            'tax_amount' => $tax,
            'grand_total' => $grandTotal
        ]);
    }

    public function processPayment(Order $order, array $paymentData)
    {
        return DB::transaction(function () use ($order, $paymentData) {
            // Create payment
            $payment = $order->payments()->create([
                'cashier_id' => $paymentData['cashier_id'],
                'payment_method' => $paymentData['payment_method'],
                'amount' => $order->grand_total,
                'change_amount' => $paymentData['change_amount'] ?? 0,
                'status' => 'completed',
                'transaction_id' => $paymentData['transaction_id'] ?? 'TRX' . now()->format('YmdHis'),
                'notes' => $paymentData['notes'] ?? null,
            ]);

            // Update order payment status
            $order->update(['payment_status' => 'paid']);

            // Deduct inventory
            $this->deductInventory($order, $paymentData['cashier_id']);

            return $payment;
        });
    }

    private function deductInventory(Order $order, $cashierId)
    {
        foreach ($order->orderDetails as $detail) {
            $menuItem = $detail->menuItem;
            
            InventoryLog::create([
                'menu_item_id' => $menuItem->id,
                'transaction_type' => 'out',
                'quantity' => $detail->quantity,
                'previous_stock' => $menuItem->stock_quantity,
                'new_stock' => $menuItem->stock_quantity - $detail->quantity,
                'user_id' => $cashierId,
                'reason' => 'Order payment: ' . $order->order_number,
                'reference_number' => $order->order_number,
            ]);

            $menuItem->decrement('stock_quantity', $detail->quantity);
            
            // Update status if out of stock
            if ($menuItem->fresh()->stock_quantity <= 0) {
                $menuItem->update(['status' => 'out_of_stock']);
            }
        }
    }

    public function cancelOrder(Order $order, $userId)
    {
        return DB::transaction(function () use ($order, $userId) {
            // Only cancel if not already served or cancelled
            if (!in_array($order->status, ['served', 'cancelled'])) {
                // Restore inventory if order was confirmed
                if (in_array($order->status, ['confirmed', 'preparing', 'ready'])) {
                    $this->restoreInventory($order, $userId);
                }

                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'unpaid'
                ]);

                // Cancel any pending payments
                $order->payments()->where('status', 'pending')->update(['status' => 'failed']);

                return true;
            }

            return false;
        });
    }

    private function restoreInventory(Order $order, $userId)
    {
        foreach ($order->orderDetails as $detail) {
            $menuItem = $detail->menuItem;
            
            InventoryLog::create([
                'menu_item_id' => $menuItem->id,
                'transaction_type' => 'in',
                'quantity' => $detail->quantity,
                'previous_stock' => $menuItem->stock_quantity,
                'new_stock' => $menuItem->stock_quantity + $detail->quantity,
                'user_id' => $userId,
                'reason' => 'Order cancellation: ' . $order->order_number,
                'reference_number' => $order->order_number,
            ]);

            $menuItem->increment('stock_quantity', $detail->quantity);
            
            // Update status if back in stock
            if ($menuItem->fresh()->stock_quantity > 0 && $menuItem->status === 'out_of_stock') {
                $menuItem->update(['status' => 'available']);
            }
        }
    }

    public function updateOrderStatus(Order $order, $status, $userId = null)
    {
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['served', 'cancelled'],
            'served' => [],
            'cancelled' => [],
        ];

        if (!in_array($status, $allowedTransitions[$order->status] ?? [])) {
            throw new \Exception("Invalid status transition from {$order->status} to {$status}");
        }

        $order->update(['status' => $status]);

        // Update timestamps
        switch ($status) {
            case 'preparing':
                $order->update(['preparation_time' => now()]);
                break;
            case 'ready':
                $order->update(['ready_time' => now()]);
                break;
            case 'served':
                $order->update(['served_time' => now()]);
                break;
        }

        return $order;
    }
}