<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ReturnDetail;
use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductRentalDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_rental_details_loads_return_records_without_requerying_invoice_items(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'daily']);
        $customer = Customer::create(['name' => 'Test Customer']);
        $product = Product::create([
            'name' => 'Test Skis',
            'price' => 25,
            'category_id' => $category->id,
        ]);
        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'active',
        ]);
        $item = InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 25,
            'total_price' => 50,
        ]);
        ReturnDetail::create([
            'invoice_id' => $invoice->id,
            'invoice_item_id' => $item->id,
            'product_id' => $product->id,
            'returned_quantity' => 1,
            'days_used' => 1,
            'cost' => 25,
            'return_date' => now(),
        ]);

        $invoiceItemQueries = 0;
        DB::listen(function (QueryExecuted $query) use (&$invoiceItemQueries): void {
            if (str_contains(strtolower($query->sql), 'invoice_items')) {
                $invoiceItemQueries++;
            }
        });

        $this->actingAs($user)
            ->get(route('products.rentalDetails', $product->id))
            ->assertOk()
            ->assertSee('Test Customer')
            ->assertSee('Test Skis');

        $this->assertSame(1, $invoiceItemQueries);
    }
}
