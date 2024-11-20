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
        Schema::create('invoice_item_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_item_id')->nullable()->constrained('invoice_items');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->enum('action', ['add', 'remove', 'update', 'partial_return']);
            $table->integer('previous_quantity')->nullable(); // Previous quantity (for updates/returns)
            $table->integer('new_quantity')->nullable(); // New quantity (for adds/updates/returns)
            $table->decimal('previous_price', 10, 2)->nullable(); // Previous price per unit (for updates)
            $table->decimal('new_price', 10, 2)->nullable(); // New price per unit (for adds/updates)
            $table->datetime('previous_rental_start_date')->nullable(); // Start of the rental period for this item
            $table->datetime('previous_rental_end_date')->nullable(); // End of the rental period for this item
            $table->integer('previous_days')->nullable(); // Previous number of rental days (for updates/returns)
            $table->integer('new_days')->nullable(); // New number of rental days (for adds/updates)
            $table->date('return_date')->nullable(); // Date of return (for returns)

            $table->text('change_reason')->nullable(); // Optional reason for change
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_item_histories');
    }
};
