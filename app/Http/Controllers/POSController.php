<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class POSController extends Controller
{
    use FileUploadTrait;

    // Display POS Interface
    public function index()
    {
        $products = Product::all();
        $customers = Customer::all();
        return view('pos.index', compact('products', 'customers'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
        ]);

        // Create the invoice
        $invoice = new Invoice();
        $invoice->customer_id = $request->customer_id;
        $invoice->total = array_sum(array_column($request->cart, 'total_price')); // Calculate total
        $invoice->status = 'active'; // Set default status
        $invoice->save();

        // Save the invoice items
        foreach ($request->cart as $item) {
            $invoice->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity'], // Calculate item total price
                'vat' => $item['vat'],
                'discount' => $item['discount'],
            ]);
        }

        // Return the newly created invoice ID to the front-end
        return response()->json(['invoice_id' => $invoice->id]);
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'required|numeric|unique:customers,phone',
            'address' => 'nullable|string',
            'deposit_card' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Optional file validation
        ]);

        // Handle file upload if provided
        $filePath = null;
        if ($request->hasFile('deposit_card')) {
            $filePath = $this->uploadImage($request, 'deposit_card', null, '/uploads/customers');
        }

        // Create new customer
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->deposit_card = $filePath;
        $customer->save();

        // Redirect back to POS page with the new customer pre-selected
        return redirect()->route('pos.index')->with('new_customer_id', $customer->id);
    }
}
