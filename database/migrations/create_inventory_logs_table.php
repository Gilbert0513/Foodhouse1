<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->enum('transaction_type', ['in', 'out', 'adjustment', 'waste', 'damaged']);
            $table->integer('quantity');
            $table->integer('previous_stock');
            $table->integer('new_stock');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->string('reference_number')->nullable();
            $table->timestamps();
            
            $table->index(['menu_item_id', 'created_at']);
            $table->index(['transaction_type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_logs');
    }
};