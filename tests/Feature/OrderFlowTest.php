<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_place_order()
    {
        // Create test users for all 3 actors
        $customer = User::factory()->create(['role' => 'customer']);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $admin = User::factory()->create(['role' => 'admin']);
        
        $menuItem = MenuItem::factory()->create(['stock_quantity' => 10]);

        $this->actingAs($customer);

        $response = $this->post('/customer/orders', [
            'order_type' => 'dine_in',
            'table_number' => 'T1',
            'pax' => 2,
            'items' => [
                [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => 2
                ]
            ]
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'status' => 'pending', // Needs cashier approval
            'payment_status' => 'unpaid'
        ]);
    }

    public function test_cashier_can_confirm_customer_order()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $cashier = User::factory()->create(['role' => 'cashier']);
        
        $menuItem = MenuItem::factory()->create(['stock_quantity' => 10]);
        
        // Customer places order
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending'
        ]);

        $this->actingAs($cashier);

        // Cashier confirms order
        $response = $this->post("/cashier/orders/{$order->id}/update-status", [
            'status' => 'confirmed'
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
            'cashier_id' => $cashier->id
        ]);
    }

    public function test_inventory_deducted_only_after_payment()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $cashier = User::factory()->create(['role' => 'cashier']);
        
        $menuItem = MenuItem::factory()->create(['stock_quantity' => 10]);
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'cashier_id' => $cashier->id,
            'status' => 'confirmed'
        ]);

        // Add order details
        $order->orderDetails()->create([
            'menu_item_id' => $menuItem->id,
            'quantity' => 2,
            'unit_price' => $menuItem->price,
            'subtotal' => $menuItem->price * 2
        ]);

        // Check inventory before payment
        $this->assertEquals(10, $menuItem->fresh()->stock_quantity);

        $this->actingAs($cashier);

        // Process payment
        $response = $this->post("/cashier/orders/{$order->id}/process-payment", [
            'payment_method' => 'cash',
            'amount' => $order->grand_total
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        // Check inventory after payment
        $this->assertEquals(8, $menuItem->fresh()->stock_quantity); // Should be 10-2=8
    }

    public function test_order_cancellation_restores_inventory()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $cashier = User::factory()->create(['role' => 'cashier']);
        
        $menuItem = MenuItem::factory()->create(['stock_quantity' => 10]);
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'cashier_id' => $cashier->id,
            'status' => 'confirmed'
        ]);

        // Add order details
        $order->orderDetails()->create([
            'menu_item_id' => $menuItem->id,
            'quantity' => 3,
            'unit_price' => $menuItem->price,
            'subtotal' => $menuItem->price * 3
        ]);

        $this->actingAs($cashier);

        // Cancel order
        $response = $this->post("/cashier/orders/{$order->id}/update-status", [
            'status' => 'cancelled'
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        // Check inventory restored
        $this->assertEquals(10, $menuItem->fresh()->stock_quantity);
    }
}

class UserAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_pages()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->actingAs($admin);
        
        $this->get('/admin/dashboard')->assertOk();
        $this->get('/admin/users')->assertOk();
        $this->get('/admin/menu')->assertOk();
    }

    public function test_cashier_cannot_access_admin_pages()
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        
        $this->actingAs($cashier);
        
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_customer_can_only_access_customer_pages()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        
        $this->actingAs($customer);
        
        $this->get('/customer/menu')->assertOk();
        $this->get('/cashier/pos')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }
}