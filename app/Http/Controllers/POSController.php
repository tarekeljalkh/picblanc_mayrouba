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
        $categoryName = session('category', 'daily');

        // Validation rules
        $rules = [
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255|required_without:customer_id',
            'customer_phone' => 'nullable|string|max:255|required_without:customer_id',
            'customer_address' => 'nullable|string|max:255|required_without:customer_id',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'total_discount' => 'nullable|numeric|min:0|max:100',
            'deposit' => 'nullable|numeric|min:0',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card',
            'note' => 'nullable',
        ];

        if ($categoryName === 'daily') {
            $rules['rental_start_date'] = 'required|date';
            $rules['rental_end_date'] = 'required|date|after_or_equal:rental_start_date';
            $rules['rental_days'] = 'required|integer|min:1';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Handle Customer
            if ($request->filled('customer_id')) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'address' => $request->customer_address,
                ]);
            }

            // Retrieve the selected category
            $category = Category::where('name', $categoryName)->firstOrFail();

            // Calculate totals
            $subtotal = 0;
            $invoiceItems = [];
            foreach ($request->cart as $cartItem) {
                $product = Product::findOrFail($cartItem['id']);
                $quantity = $cartItem['quantity'];
                $price = $product->price;

                $totalPrice = ($categoryName === 'daily')
                    ? $quantity * $price * $request->rental_days
                    : $quantity * $price;

                $invoiceItems[] = new InvoiceItem([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'rental_start_date' => $categoryName === 'daily' ? $request->rental_start_date : null,
                    'rental_end_date' => $categoryName === 'daily' ? $request->rental_end_date : null,
                    'days' => $categoryName === 'daily' ? $request->rental_days : null,
                    'returned_quantity' => 0,
                    'added_quantity' => 0,
                ]);

                $subtotal += $totalPrice;
            }

            // Discount calculations
            $totalDiscount = $request->total_discount ?? 0;
            $discountAmount = ($subtotal * $totalDiscount) / 100;

            // Calculate the full total amount (before deposit)
            $totalAmount = $subtotal - $discountAmount;

            // Track deposit and payment
            $deposit = $request->deposit ?? 0;
            $paymentAmount = $request->payment_amount ?? 0;

            // Ensure payment and deposit do not exceed the total
            if ($deposit + $paymentAmount > $totalAmount) {
                return response()->json(['error' => 'Payment and deposit exceed total amount.'], 400);
            }

            // Paid amount includes only additional payments beyond the deposit
            $paidAmount = $paymentAmount;

            // Create the Invoice
            $invoiceData = [
                'customer_id' => $customer->id,
                'user_id' => auth()->user()->id,
                'category_id' => $category->id,
                'total_discount' => $totalDiscount,
                'deposit' => $deposit,
                'total_amount' => $totalAmount, // Full amount before subtracting deposit
                'paid_amount' => $paidAmount, // Additional payments beyond deposit
                'payment_method' => $request->payment_method,
                'note' => $request->note,
            ];

            if ($categoryName === 'daily') {
                $invoiceData['rental_start_date'] = $request->rental_start_date;
                $invoiceData['rental_end_date'] = $request->rental_end_date;
                $invoiceData['days'] = $request->rental_days;
            }

            $invoice = Invoice::create($invoiceData);

            // Attach items to the invoice
            $invoice->items()->saveMany($invoiceItems);

            DB::commit();

            return response()->json(['invoice_id' => $invoice->id, 'message' => 'Checkout successful.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Exception:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json(['error' => 'An error occurred while processing checkout.'], 500);
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
            'phone' => 'required|numeric|unique:customers,phone,phone2',
            'phone2' => 'nullable|numeric|unique:customers,phone,phone2',
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
        $customer->phone2 = $request->phone2;
        $customer->address = $request->address;
        $customer->deposit_card = $filePath;
        $customer->save();

        // Redirect back to POS page with the new customer pre-selected
        return redirect()->route('pos.index')->with('new_customer_id', $customer->id);
    }
}
