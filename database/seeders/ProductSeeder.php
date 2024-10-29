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
        Product::create(['name' => 'Ski', 'description' => 'Salomon', 'price' => 150.00]);
        Product::create(['name' => 'Ski Boots', 'description' => 'Burton', 'price' => 200.00]);
        Product::create(['name' => 'Poles', 'description' => 'Atomic', 'price' => 100.00]);
        Product::create(['name' => 'Snowboard', 'description' => 'Giro', 'price' => 50.00]);
        Product::create(['name' => 'Snowboard Boots', 'description' => 'North Face', 'price' => 20.00]);
        Product::create(['name' => 'Sled', 'description' => 'North Face', 'price' => 200.00]);
        Product::create(['name' => 'Hiking Racket', 'description' => 'North Face', 'price' => 10.00]);
        Product::create(['name' => 'Gloves', 'description' => 'North Face', 'price' => 150.00]);
        Product::create(['name' => 'Helmet', 'description' => 'Marmot', 'price' => 30.00]);
        Product::create(['name' => 'Goggles', 'description' => 'Marmot', 'price' => 60.00]);
        Product::create(['name' => 'Jacket', 'description' => 'Columbia', 'price' => 20.00]);
        Product::create(['name' => 'Pants', 'description' => 'Columbia', 'price' => 70.00]);
        Product::create(['name' => 'Apres Ski', 'description' => 'Columbia', 'price' => 180.00]);

    }
}
