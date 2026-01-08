<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cashier_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in');
            $table->string('table_number')->nullable();
            $table->integer('pax')->default(1);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'served', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->text('special_instructions')->nullable();
            $table->timestamp('order_time')->useCurrent();
            $table->timestamp('preparation_time')->nullable();
            $table->timestamp('ready_time')->nullable();
            $table->timestamp('served_time')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};