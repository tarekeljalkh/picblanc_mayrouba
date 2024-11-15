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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity'); // Quantity of the product rented
            $table->integer('quantity_remaining')->default(0); // Track remaining quantity for partial returns
            $table->decimal('price', 10, 2); // Price per unit
            $table->decimal('total_price', 10, 2); // Total price after VAT and discount
            $table->boolean('damaged')->default(false); // Damage status
            $table->decimal('damage_charge', 10, 2)->default(0); // Charge for damage
            $table->datetime('rental_start_date')->nullable(); // Start of the rental period for this item
            $table->datetime('rental_end_date')->nullable(); // End of the rental period for this item
            $table->timestamps();
            $table->softDeletes(); // Add this line to enable soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
