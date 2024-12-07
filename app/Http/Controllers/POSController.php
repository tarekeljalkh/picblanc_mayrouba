<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    use FileUploadTrait;

    // Display POS Interface
    public function index()
    {
        $products = Product::whereHas('category', function ($query) {
            $query->where('name', session('category', 'daily'));
        })->get();

        $customers = Customer::all();

        return view('pos.index', compact('products', 'customers'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'cart' => 'required|array',
            'cart.*.id' => [
                'required',
                'exists:products,id',
                function ($attribute, $value, $fail) {
                    $currentCategoryName = session('category', 'daily');
                    $category = Category::where('name', $currentCategoryName)->firstOrFail();
                    $product = Product::find($value);
                    if ($product && $product->category_id !== $category->id) {
                        $fail("The product '{$product->name}' does not belong to the selected category.");
                    }
                }
            ],
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
            'payment_method' => 'required|in:cash,credit_card',
            'rental_days' => 'required|integer|min:1',
            'rental_start_date' => 'required|date_format:Y-m-d\TH:i',
            'rental_end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:rental_start_date',
        ]);

        try {
            DB::beginTransaction();

            $currentCategoryName = session('category', 'daily');
            $category = Category::where('name', $currentCategoryName)->firstOrFail();

            $subtotal = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $request->cart));

            $rentalTotal = $subtotal * $request->rental_days;
            $discountAmount = ($rentalTotal * ($request->total_discount ?? 0)) / 100;
            $totalAmount = $rentalTotal - $discountAmount;

            $isPaid = (bool) $request->status;
            $invoiceStatus = $isPaid ? 'active' : 'draft';

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'category_id' => $category->id,
                'total_discount' => $request->total_discount ?? 0,
                'amount_per_day' => $subtotal,
                'total_amount' => $totalAmount,
                'paid' => $isPaid,
                'payment_method' => $request->payment_method,
                'days' => $request->rental_days,
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
                'status' => $invoiceStatus,
                'note' => $request->note ?? null,
            ]);

            foreach ($request->cart as $item) {
                $product = Product::findOrFail($item['id']);
                $price = $product->price;
                $invoice->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total_price' => $price * $item['quantity'],
                ]);
            }

            DB::commit();

            return response()->json(['invoice_id' => $invoice->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // public function checkout(Request $request)
    // {
    //     $request->validate([
    //         'customer_id' => 'required|exists:customers,id',
    //         'cart' => 'required|array',
    //         'cart.*.id' => 'required|exists:products,id',
    //         'cart.*.quantity' => 'required|integer|min:1',
    //         'cart.*.price' => 'required|numeric|min:0',
    //         'total_discount' => 'nullable|numeric|min:0',
    //         'status' => 'required|in:paid,unpaid',
    //         'payment_method' => 'required|in:cash,credit_card',
    //         'rental_days' => 'required|integer|min:1', // Use rental_days from the request
    //         'rental_start_date' => 'required|date',
    //         'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
    //     ]);

    //     // Get current category from session
    //     $currentCategoryName = session('category', 'daily'); // Default to 'daily'

    //     // Fetch the category ID based on the current category name
    //     $category = Category::where('name', $currentCategoryName)->firstOrFail();


    //     // Calculate the subtotal for one day
    //     $subtotal = array_sum(array_map(function ($item) {
    //         return $item['price'] * $item['quantity'];
    //     }, $request->cart));

    //     // Calculate the rental total based on rental_days
    //     $rentalTotal = $subtotal * $request->rental_days;

    //     // Calculate discount amounts
    //     $discountAmount = ($rentalTotal * ($request->total_discount ?? 0)) / 100;

    //     // Calculate the final total amount including discount
    //     $totalAmount = $rentalTotal - $discountAmount;

    //     // Create the invoice with rental_days
    //     $invoice = Invoice::create([
    //         'customer_id' => $request->customer_id,
    //         'user_id' => Auth::id(),
    //         'category_id' => $category->id, // Associate the invoice with the selected category
    //         'total_discount' => $request->total_discount ?? 0,
    //         'amount_per_day' => $subtotal,
    //         'total_amount' => $totalAmount, // Final total with discount applied
    //         'paid' => $request->status === 'paid',
    //         'payment_method' => $request->payment_method,
    //         'days' => $request->rental_days, // Store rental_days as days in the database
    //         'rental_start_date' => $request->rental_start_date,
    //         'rental_end_date' => $request->rental_end_date,
    //         'status' => 'active',
    //         'note' => $request->note, // Save the note
    //     ]);

    //     // Save invoice items
    //     foreach ($request->cart as $item) {
    //         $invoice->items()->create([
    //             'product_id' => $item['id'],
    //             'quantity' => $item['quantity'],
    //             'price' => $item['price'],
    //             'total_price' => $item['price'] * $item['quantity'],
    //         ]);
    //     }

    //     return response()->json(['invoice_id' => $invoice->id]);
    // }


    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|min:3',
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
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->deposit_card = $filePath;
        $customer->save();

        // Redirect back to POS page with the new customer pre-selected
        return redirect()->route('pos.index')->with('new_customer_id', $customer->id);
    }
}
