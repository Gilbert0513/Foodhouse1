<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_required', 10, 2);
            $table->string('unit');
            $table->timestamps();
            
            $table->unique(['menu_item_id', 'ingredient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_ingredients');
    }
};