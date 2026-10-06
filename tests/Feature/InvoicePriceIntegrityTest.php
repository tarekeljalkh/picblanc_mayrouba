<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePriceIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_uses_catalog_price_instead_of_submitted_product_price(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'season']);
        $customer = Customer::create(['name' => 'Test Customer']);
        $product = Product::create([
            'name' => 'Catalog Item',
            'price' => 40,
            'category_id' => $category->id,
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'season'])
            ->postJson(route('pos.checkout'), [
                'customer_id' => $customer->id,
                'cart' => [[
                    'id' => $product->id,
                    'quantity' => 2,
                    'price' => 0.01,
                    'name' => 'Catalog Item',
                ]],
                'total_discount' => 0,
                'deposit' => 0,
                'payment_amount' => 0,
                'payment_method' => 'cash',
            ])
            ->assertOk();

        $this->assertDatabaseHas('invoice_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 40,
            'total_price' => 80,
        ]);
    }

    public function test_invoice_form_uses_catalog_price_instead_of_submitted_product_price(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'season']);
        $customer = Customer::create(['name' => 'Test Customer']);
        $product = Product::create([
            'name' => 'Catalog Item',
            'price' => 25,
            'category_id' => $category->id,
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'season'])
            ->post(route('invoices.store'), [
                'customer_id' => $customer->id,
                'products' => [$product->id],
                'quantities' => [3],
                'prices' => [0.01],
                'custom_items' => [],
                'total_discount' => 0,
                'deposit' => 0,
                'payment_amount' => 0,
                'payment_method' => 'cash',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('invoice_items', [
            'product_id' => $product->id,
            'quantity' => 3,
            'price' => 25,
            'total_price' => 75,
        ]);
    }

    public function test_checkout_rejects_a_product_from_another_category(): void
    {
        $user = User::factory()->create();
        $season = Category::create(['name' => 'season']);
        $daily = Category::create(['name' => 'daily']);
        $customer = Customer::create(['name' => 'Test Customer']);
        $product = Product::create([
            'name' => 'Daily Item',
            'price' => 10,
            'category_id' => $daily->id,
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'season'])
            ->postJson(route('pos.checkout'), [
                'customer_id' => $customer->id,
                'cart' => [[
                    'id' => $product->id,
                    'quantity' => 1,
                    'price' => 10,
                ]],
                'payment_method' => 'cash',
            ])
            ->assertUnprocessable();
    }

    public function test_pos_calculates_rental_days_from_dates_not_submitted_day_count(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'daily']);
        $customer = Customer::create(['name' => 'Test Customer']);
        $product = Product::create([
            'name' => 'Daily Item',
            'price' => 10,
            'category_id' => $category->id,
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'daily'])
            ->postJson(route('pos.checkout'), [
                'customer_id' => $customer->id,
                'cart' => [[
                    'id' => $product->id,
                    'quantity' => 2,
                    'price' => 0.01,
                ]],
                'rental_start_date' => '2026-05-10 00:00:00',
                'rental_end_date' => '2026-05-12 00:00:00',
                'rental_days' => 1,
                'payment_method' => 'cash',
            ])
            ->assertOk();

        $this->assertDatabaseHas('invoice_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 10,
            'total_price' => 60,
            'days' => 3,
        ]);
    }

    public function test_invoice_form_calculates_rental_days_from_dates(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'daily']);
        $customer = Customer::create(['name' => 'Test Customer']);
        $product = Product::create([
            'name' => 'Daily Item',
            'price' => 10,
            'category_id' => $category->id,
        ]);

        $this->actingAs($user)
            ->withSession(['category' => 'daily'])
            ->post(route('invoices.store'), [
                'customer_id' => $customer->id,
                'products' => [$product->id],
                'quantities' => [2],
                'prices' => [0.01],
                'custom_items' => [],
                'rental_start_date' => '2026-05-10 00:00:00',
                'rental_end_date' => '2026-05-12 00:00:00',
                'days' => 1,
                'total_discount' => 0,
                'deposit' => 0,
                'payment_amount' => 0,
                'payment_method' => 'cash',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('invoice_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 10,
            'total_price' => 60,
            'days' => 3,
        ]);
    }
}
