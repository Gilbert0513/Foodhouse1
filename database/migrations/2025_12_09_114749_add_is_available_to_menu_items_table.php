<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('image');
            $table->boolean('track_inventory')->default(false)->after('is_available');
            $table->enum('inventory_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock')->after('track_inventory');
            $table->decimal('preparation_time', 5, 2)->nullable()->after('inventory_status');
        });
    }

    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'track_inventory', 'inventory_status', 'preparation_time']);
        });
    }
};