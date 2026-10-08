<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomItem;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_invoice_counts_using_batched_invoice_data(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'daily']);
        $customer = Customer::create(['name' => 'Dashboard Customer']);

        $paidInvoice = Invoice::create([
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'active',
        ]);
        CustomItem::create([
            'invoice_id' => $paidInvoice->id,
            'name' => 'Returned item',
            'price' => 10,
            'quantity' => 1,
            'returned_quantity' => 1,
        ]);
        InvoicePayment::create([
            'invoice_id' => $paidInvoice->id,
            'amount' => 10,
            'payment_method' => 'cash',
            'payment_date' => now(),
        ]);

        $unpaidInvoice = Invoice::create([
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'active',
        ]);
        CustomItem::create([
            'invoice_id' => $unpaidInvoice->id,
            'name' => 'Unreturned item',
            'price' => 8,
            'quantity' => 2,
            'returned_quantity' => 0,
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'daily'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('text-primary">1</h4>', false)
            ->assertSee('text-success">2</h4>', false)
            ->assertSee('text-info">1</h4>', false)
            ->assertSee('text-danger">1</h4>', false)
            ->assertSee('text-secondary">1</h4>', false)
            ->assertSee('text-dark">1</h4>', false);
    }
}
