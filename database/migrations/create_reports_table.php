<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type');
            $table->date('report_date');
            $table->json('data');
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('total_customers')->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('total_profit', 12, 2)->default(0);
            $table->timestamps();
            
            $table->index(['report_type', 'report_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};