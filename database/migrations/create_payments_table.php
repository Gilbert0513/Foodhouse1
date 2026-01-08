<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('cashier_id')->constrained('users')->onDelete('cascade');
            $table->string('payment_reference')->unique()->nullable();
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'gcash', 'paymaya', 'bank_transfer']);
            $table->decimal('amount', 12, 2);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['order_id', 'status']);
            $table->index(['cashier_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};