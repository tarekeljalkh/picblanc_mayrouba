<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
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
        Log::info('Checkout Request Payload:', $request->all());

        // Validate input
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'total_discount' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
            'payment_method' => 'required|in:cash,credit_card',
            'rental_days' => 'required|integer|min:1',
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
        ]);

        try {
            DB::beginTransaction();

            // Handle Customer
            if ($request->filled('customer_id')) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                throw new \Exception('Customer selection is required.');
            }

            // Retrieve the category
            $categoryName = session('category', 'daily');
            $category = Category::where('name', $categoryName)->firstOrFail();

            // Calculate totals
            $rentalStartDate = Carbon::parse($request->rental_start_date);
            $rentalEndDate = Carbon::parse($request->rental_end_date);
            $rentalDays = $rentalStartDate->diffInDays($rentalEndDate) + 1;

            $subtotal = 0;
            $invoiceItems = [];
            foreach ($request->cart as $item) {
                $product = Product::findOrFail($item['id']);
                $quantity = $item['quantity'];
                $price = $product->price;
                $totalPrice = $price * $quantity * $rentalDays;

                $subtotal += $totalPrice;

                $invoiceItems[] = new InvoiceItem([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'rental_start_date' => $request->rental_start_date,
                    'rental_end_date' => $request->rental_end_date,
                    'days' => $rentalDays,
                    'returned_quantity' => 0,
                    'added_quantity' => 0,
                ]);
            }

            $discountAmount = ($subtotal * ($request->total_discount ?? 0)) / 100;
            $rentalTotal = $subtotal - $discountAmount;

            // Create the invoice
            $invoice = new Invoice([
                'customer_id' => $customer->id,
                'user_id' => Auth::id(),
                'category_id' => $category->id,
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
                'total_discount' => $request->total_discount,
                'total_amount' => $rentalTotal,
                'paid' => $request->status,
                'payment_method' => $request->payment_method,
                'status' => $request->status ? 'active' : 'draft',
                'days' => $rentalDays,
            ]);

            $invoice->save();
            $invoice->items()->saveMany($invoiceItems);

            DB::commit();

            return response()->json(['invoice_id' => $invoice->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Exception:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
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
