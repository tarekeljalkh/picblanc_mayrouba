<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create dummy products
        Product::create(['name' => 'Ski', 'brand' => 'Salomon', 'stock' => 50, 'price' => 150.00]);
        Product::create(['name' => 'Ski Boots', 'brand' => 'Burton', 'stock' => 30, 'price' => 200.00]);
        Product::create(['name' => 'Poles', 'brand' => 'Atomic', 'stock' => 70, 'price' => 100.00]);
        Product::create(['name' => 'Snowboard', 'brand' => 'Giro', 'stock' => 100, 'price' => 50.00]);
        Product::create(['name' => 'Snowboard Boots', 'brand' => 'North Face', 'stock' => 200, 'price' => 20.00]);
        Product::create(['name' => 'Sled', 'brand' => 'North Face', 'stock' => 200, 'price' => 200.00]);
        Product::create(['name' => 'Hiking Racket', 'brand' => 'North Face', 'stock' => 200, 'price' => 10.00]);
        Product::create(['name' => 'Gloves', 'brand' => 'North Face', 'stock' => 200, 'price' => 150.00]);
        Product::create(['name' => 'Helmet', 'brand' => 'Marmot', 'stock' => 200, 'price' => 30.00]);
        Product::create(['name' => 'Goggles', 'brand' => 'Marmot', 'stock' => 200, 'price' => 60.00]);
        Product::create(['name' => 'Jacket', 'brand' => 'Columbia', 'stock' => 200, 'price' => 20.00]);
        Product::create(['name' => 'Pants', 'brand' => 'Columbia', 'stock' => 200, 'price' => 70.00]);
        Product::create(['name' => 'Apres Ski', 'brand' => 'Columbia', 'stock' => 200, 'price' => 180.00]);

    }
}
