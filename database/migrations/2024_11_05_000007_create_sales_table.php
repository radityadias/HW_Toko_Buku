<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('sale_id'); 
            $table->unsignedInteger('book_id'); 
            $table->unsignedInteger('customer_id'); 
            $table->float('total_price');
            $table->date('sale_date');

            // Define foreign keys after defining the fields
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
