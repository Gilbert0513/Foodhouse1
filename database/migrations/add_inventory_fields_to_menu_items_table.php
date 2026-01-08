<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->boolean('track_inventory')->default(false);
            $table->enum('inventory_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            $table->decimal('preparation_time', 5, 2)->nullable(); // in minutes
        });
    }

    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['track_inventory', 'inventory_status', 'preparation_time']);
        });
    }
};