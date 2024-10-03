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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->decimal('total', 10, 2); // Total amount of the invoice
            $table->boolean('paid')->default(false); // Paid status
            $table->enum('status', ['active', 'returned', 'overdue'])->default('active'); // Rental status
            $table->date('rental_start_date')->nullable(); // Start of the rental period
            $table->date('rental_end_date')->nullable(); // End of the rental period
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
