<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class POSController extends Controller
{
        // Display POS Interface
        public function index()
        {
            $products = Product::all();
            $customers = Customer::all();
            return view('pos.index', compact('products', 'customers'));
        }

}
