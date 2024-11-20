<?php
namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InvoiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure that invoices and products exist
        $invoice1 = Invoice::first() ?? Invoice::factory()->create();
        $invoice2 = Invoice::find(2) ?? Invoice::factory()->create();

        $product1 = Product::first() ?? Product::factory()->create();
        $product2 = Product::find(2) ?? Product::factory()->create();
        $product3 = Product::find(3) ?? Product::factory()->create();

        // Add items to the first invoice
        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 150.00,
            'total_price' => 2 * 150.00, // Calculate total price as quantity * price
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 200.00,
            'total_price' => 1 * 200.00, // Calculate total price
        ]);

        // Add items to the second invoice
        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'product_id' => $product3->id,
            'quantity' => 3,
            'price' => 100.00,
            'total_price' => 3 * 100.00, // Calculate total price
        ]);
    }
}
