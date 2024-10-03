<?php
namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure that at least two customers exist, otherwise create them
        $customer1 = Customer::first() ?? Customer::factory()->create();
        $customer2 = Customer::find(2) ?? Customer::factory()->create();

        // Create dummy invoices
        Invoice::create([
            'customer_id' => $customer1->id,
            'total' => 400,
            'paid' => false,
            'status' => 'active'
        ]);

        Invoice::create([
            'customer_id' => $customer2->id,
            'total' => 300,
            'paid' => true,
            'status' => 'returned'
        ]);
    }
}
