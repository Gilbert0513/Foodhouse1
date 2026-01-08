<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit'); // kg, g, l, ml, piece, etc.
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
};