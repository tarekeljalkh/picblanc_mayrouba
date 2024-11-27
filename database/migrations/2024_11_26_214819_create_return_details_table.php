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
        Schema::create('return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade'); // Link to invoices table
            $table->foreignId('invoice_item_id')->constrained()->onDelete('cascade'); // Link to invoice_items table
            $table->integer('returned_quantity');
            $table->integer('days_used'); // Tracks how many days the item was used
            $table->decimal('cost', 10, 2); // Cost incurred for the returned items
            $table->date('return_date'); // Date of the return
            $table->timestamps();

            // Indexes for faster lookups
            $table->index('invoice_id');
            $table->index('invoice_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};
