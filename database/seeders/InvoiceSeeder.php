<?php
namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
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
        $customer2 = Customer::skip(1)->first() ?? Customer::factory()->create();

        // Ensure there is a user for seeding purposes
        $user = User::first() ?? User::factory()->create();

        // Set rental dates and calculate days between them
        $startDate1 = Carbon::now()->subDays(10);
        $endDate1 = Carbon::now()->subDays(5);
        $days1 = $startDate1->diffInDays($endDate1) + 1;

        $startDate2 = Carbon::now()->subDays(20);
        $endDate2 = Carbon::now()->subDays(15);
        $days2 = $startDate2->diffInDays($endDate2) + 1;

        // Create dummy invoices
        Invoice::create([
            'customer_id' => $customer1->id,
            'user_id' => $user->id, // Assigning user_id
            'payment_method' => 'cash', // Setting payment method
            'rental_start_date' => $startDate1->format('Y-m-d'),
            'rental_end_date' => $endDate1->format('Y-m-d'),
            'days' => $days1,
            'amount_per_day' => 100, // Assuming a fixed amount per day for seeding
            'total_vat' => 10, // 10% VAT
            'total_discount' => 5, // 5% Discount
            'total_amount' => (100 * $days1) * 1.1 * 0.95, // Calculate total with VAT and discount
            'paid' => false,
            'status' => 'active'
        ]);

        Invoice::create([
            'customer_id' => $customer2->id,
            'user_id' => $user->id, // Assigning user_id
            'payment_method' => 'credit_card', // Setting payment method
            'rental_start_date' => $startDate2->format('Y-m-d'),
            'rental_end_date' => $endDate2->format('Y-m-d'),
            'days' => $days2,
            'amount_per_day' => 150, // Assuming a fixed amount per day for seeding
            'total_vat' => 8, // 8% VAT
            'total_discount' => 3, // 3% Discount
            'total_amount' => (150 * $days2) * 1.08 * 0.97, // Calculate total with VAT and discount
            'paid' => true,
            'status' => 'returned'
        ]);
    }
}
