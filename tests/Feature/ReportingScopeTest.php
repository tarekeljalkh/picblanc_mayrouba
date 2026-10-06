<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomItem;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReportingScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_product_report_excludes_other_users_custom_items(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'season']);
        $customer = Customer::create(['name' => 'Test Customer']);

        $ownInvoice = Invoice::create([
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'active',
        ]);
        CustomItem::create([
            'invoice_id' => $ownInvoice->id,
            'name' => 'Own Custom Item',
            'price' => 10,
            'quantity' => 1,
        ]);

        $otherInvoice = Invoice::create([
            'customer_id' => $customer->id,
            'user_id' => $otherUser->id,
            'category_id' => $category->id,
            'status' => 'active',
        ]);
        CustomItem::create([
            'invoice_id' => $otherInvoice->id,
            'name' => 'Other User Secret Item',
            'price' => 100,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'season'])
            ->get(route('trialbalance.products'))
            ->assertOk()
            ->assertSee('Own Custom Item')
            ->assertDontSee('Other User Secret Item');
    }

    public function test_invoice_date_filter_includes_a_rental_that_covers_the_selected_range(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'daily']);
        $customer = Customer::create(['name' => 'Long Rental Customer']);
        Invoice::create([
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'active',
            'rental_start_date' => '2025-01-01 00:00:00',
            'rental_end_date' => '2027-01-01 00:00:00',
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'daily'])
            ->get(route('invoices.index', [
                'start_date' => '2026-05-10',
                'end_date' => '2026-05-12',
            ]))
            ->assertOk()
            ->assertSee('Long Rental Customer');
    }

    public function test_trial_balance_rejects_invalid_date_filters(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('trialbalance.index', ['from_date' => 'not-a-date']))
            ->assertSessionHasErrors('from_date');
    }

    public function test_invoice_schema_has_fields_used_by_runtime_workflows(): void
    {
        foreach (['total_amount', 'paid_amount', 'paid', 'total', 'amount_per_day', 'payment_method'] as $column) {
            $this->assertTrue(Schema::hasColumn('invoices', $column), "Missing invoices.{$column} column.");
        }
    }
}
