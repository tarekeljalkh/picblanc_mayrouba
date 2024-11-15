<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'total_vat' => 'required|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|in:cash,credit_card',
            'rental_days' => 'required|integer|min:1', // Use rental_days from the request
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
        ]);

        // Calculate the subtotal for one day
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $request->cart));

        // Calculate the rental total based on rental_days
        $rentalTotal = $subtotal * $request->rental_days;

        // Calculate VAT and discount amounts
        $vatAmount = ($rentalTotal * $request->total_vat) / 100;
        $discountAmount = ($rentalTotal * ($request->total_discount ?? 0)) / 100;

        // Calculate the final total amount including VAT and discount
        $totalAmount = $rentalTotal + $vatAmount - $discountAmount;

        // Create the invoice with rental_days
        $invoice = Invoice::create([
            'customer_id' => $request->customer_id,
            'user_id' => Auth::id(),
            'total_vat' => $request->total_vat,
            'total_discount' => $request->total_discount ?? 0,
            'amount_per_day' => $subtotal,
            'total_amount' => $totalAmount, // Final total with VAT and discount applied
            'paid' => $request->status === 'paid',
            'payment_method' => $request->payment_method,
            'days' => $request->rental_days, // Store rental_days as days in the database
            'rental_start_date' => $request->rental_start_date,
            'rental_end_date' => $request->rental_end_date,
            'status' => 'active',
            'note' => $request->note, // Save the note
        ]);

        // Save invoice items
        foreach ($request->cart as $item) {
            $invoice->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

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
