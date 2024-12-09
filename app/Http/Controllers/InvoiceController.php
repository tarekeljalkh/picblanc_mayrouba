<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     // Retrieve the selected category from the session, default to 'daily' if none is set
    //     $selectedCategory = session('category', 'daily');

    //     // Fetch invoices based on the selected category
    //     $invoices = Invoice::with('customer')
    //         ->whereHas('category', function ($query) use ($selectedCategory) {
    //             $query->where('name', $selectedCategory);
    //         })
    //         ->get();

    //     // Pass the selected category to the view
    //     return view('invoices.index', compact('invoices', 'selectedCategory'));
    // }

    public function index(Request $request)
    {
        // Retrieve the selected category from the session, default to 'daily' if none is set
        $selectedCategory = session('category', 'daily');

        // Get the status and payment filters from the request
        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');

        // Fetch invoices based on the selected category and optional status/payment filters
        $invoices = Invoice::with('customer')
            ->whereHas('category', function ($query) use ($selectedCategory) {
                $query->where('name', $selectedCategory);
            })
            ->when($status, function ($query, $status) {
                // Filter by invoice status
                $query->where('status', $status);
            })
            ->when($paymentStatus === 'paid', function ($query) {
                // Filter for paid invoices
                $query->where('paid', true);
            })
            ->when($paymentStatus === 'unpaid', function ($query) {
                // Filter for unpaid invoices
                $query->where('paid', false);
            })
            ->paginate(10); // Paginate results for better performance

        // Pass the selected category, status, and payment status to the view
        return view('invoices.index', compact('invoices', 'selectedCategory', 'status', 'paymentStatus'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('invoices.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255|required_without:customer_id',
            'customer_phone' => 'nullable|string|max:255|required_without:customer_id',
            'customer_address' => 'nullable|string|max:255|required_without:customer_id',
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'integer|min:1',
            'prices' => 'required|array|min:1',
            'prices.*' => 'numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'paid' => 'required|in:0,1',
            'payment_method' => 'required|in:cash,credit_card', // Validate payment method
            'amount_per_day' => 'required|numeric', // Validating amount per day from the form
            'days' => 'required|integer', // Validating days from the form
            'total_amount' => 'required|numeric' // Validating total from the form
        ]);

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

        // Retrieve the selected category from the session
        $categoryName = session('category', 'daily');
        $category = Category::where('name', $categoryName)->firstOrFail();

        // Determine the invoice status based on the payment status
        $status = $request->paid ? 'active' : 'draft';

        // Create the Invoice
        $invoice = new Invoice([
            'customer_id' => $customer->id,
            'user_id' => auth()->user()->id,
            'category_id' => $category->id, // Associate the invoice with the selected category
            'rental_start_date' => $request->rental_start_date,
            'rental_end_date' => $request->rental_end_date,
            'total_discount' => $request->total_discount ?? 0,
            'amount_per_day' => $request->amount_per_day,
            'total_amount' => $request->total_amount,
            'paid' => $request->paid,
            'payment_method' => $request->payment_method, // Store payment method
            'status' => $status, // Set the status dynamically
            'days' => $request->days,
        ]);
        $invoice->save();

        // Calculate and Add Invoice Items
        $rentalStartDate = Carbon::parse($request->rental_start_date);
        $rentalEndDate = Carbon::parse($request->rental_end_date);
        $rentalDays = $rentalStartDate->diffInDays($rentalEndDate) + 1;

        $invoiceItems = [];
        foreach ($request->products as $index => $product_id) {
            $quantity = $request->quantities[$index];
            $price = $request->prices[$index];
            $totalPrice = $quantity * $price * $rentalDays;

            $invoiceItems[] = new InvoiceItem([
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
                'days' => $rentalDays,
                'returned_quantity' => 0, // Initial value
                'added_quantity' => 0, // Initial value
            ]);
        }

        // Attach items to the invoice
        $invoice->items()->saveMany($invoiceItems);

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice created successfully');
    }



    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with('items.product', 'additionalItems', 'returnDetails')->findOrFail($id);

        $totals = $invoice->calculateTotals();

        return view('invoices.show', compact('invoice', 'totals'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'integer|min:1',
            'prices' => 'required|array|min:1',
            'prices.*' => 'numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'paid' => 'required|in:0,1',
        ]);

        $invoice = Invoice::findOrFail($id);

        // Update customer if a new customer_id is provided
        if ($request->filled('customer_id') && $request->customer_id != $invoice->customer_id) {
            $invoice->customer_id = $request->customer_id;
        }

        // Calculate rental days for updated items
        $rentalStartDate = Carbon::parse($request->rental_start_date);
        $rentalEndDate = Carbon::parse($request->rental_end_date);
        $rentalDays = $rentalStartDate->diffInDays($rentalEndDate) + 1;

        // Update invoice details
        $invoice->total_discount = $request->total_discount ?? 0;
        $invoice->paid = (bool) $request->paid;
        $invoice->status = 'active';

        // Recalculate subtotal, discount, and total for updated items
        $subtotal = 0;
        $invoice->items()->delete();
        $invoiceItems = [];

        foreach ($request->products as $index => $product_id) {
            $quantity = $request->quantities[$index];
            $price = $request->prices[$index];
            $totalPrice = $quantity * $price * $rentalDays;
            $subtotal += $totalPrice;

            $invoiceItems[] = new InvoiceItem([
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
            ]);
        }

        // Calculate total and discount
        $discountAmount = ($subtotal * $invoice->total_discount) / 100;
        $invoice->total = $subtotal - $discountAmount;
        $invoice->save();

        // Attach updated items to the invoice
        $invoice->items()->saveMany($invoiceItems);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }

    public function print($id)
    {
        $invoice = Invoice::with('items.product', 'additionalItems', 'returnDetails')->findOrFail($id);

        $totals = $invoice->calculateTotals();

        return view('invoices.print', compact('invoice', 'totals'));
    }


    /**
     * Download the invoice as PDF.
     */
    public function download($id)
    {
        $invoice = Invoice::with('items.product', 'additionalItems', 'returnDetails')->findOrFail($id);

        $totals = $invoice->calculateTotals();

        $pdf = Pdf::loadView('invoices.download', compact('invoice', 'totals'));

        return $pdf->download("invoice-{$invoice->id}.pdf");
    }


    public function processReturns(Request $request, $invoiceId)
    {
        $invoice = Invoice::with('items.product')->findOrFail($invoiceId);

        $validated = $request->validate([
            'returns' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->returns as $key => $return) {
                // Skip unselected items
                if (!isset($return['selected'])) {
                    continue;
                }

                $item = $invoice->items->find($key);
                if (!$item) {
                    return redirect()->back()->withErrors([
                        "returns.{$key}.item_id" => "The selected item is invalid.",
                    ]);
                }

                // Validate return fields
                $validatedReturn = Validator::make($return, [
                    'quantity' => 'required|integer|min:1|max:' . ($item->quantity - $item->returned_quantity),
                    'return_date' => 'required|date|after_or_equal:' . $invoice->rental_start_date . '|before_or_equal:' . $invoice->rental_end_date,
                ])->validate();

                // Calculate the cost of the return
                $returnDate = Carbon::parse($validatedReturn['return_date']);
                $usedDays = $returnDate->diffInDays(Carbon::parse($invoice->rental_start_date)) + 1;
                $usedCost = $item->price * $usedDays * $validatedReturn['quantity'];

                // Create the return record
                ReturnDetail::create([
                    'invoice_id' => $invoice->id,
                    'invoice_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'returned_quantity' => $validatedReturn['quantity'],
                    'days_used' => $usedDays,
                    'cost' => $usedCost,
                    'return_date' => $validatedReturn['return_date'],
                ]);

                // Update the item's returned quantity
                $item->increment('returned_quantity', $validatedReturn['quantity']);

                // Update the invoice total amount
                $invoice->decrement('total_amount', $usedCost);
            }

            // Check if all items in the invoice are returned
            $allReturned = $invoice->items->every(function ($item) {
                return $item->quantity === $item->returned_quantity;
            });

            if ($allReturned) {
                $invoice->status = 'returned'; // Update the status to 'returned'
            }

            $invoice->save();
            DB::commit();

            return redirect()->route('invoices.show', $invoiceId)->with('success', 'Returns processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process returns: ' . $e->getMessage());
        }
    }


    public function addItems(Request $request, $invoiceId)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.rental_start_date' => 'required|date',
            'products.*.rental_end_date' => 'required|date|after_or_equal:products.*.rental_start_date',
        ]);

        // Find the invoice
        $invoice = Invoice::findOrFail($invoiceId);

        DB::beginTransaction();

        try {
            foreach ($validated['products'] as $product) {
                $productId = $product['product_id'];
                $quantity = $product['quantity'];
                $price = $product['price'];
                $rentalStartDate = Carbon::parse($product['rental_start_date']);
                $rentalEndDate = Carbon::parse($product['rental_end_date']);

                // Calculate the number of days for the rental period
                $days = max($rentalStartDate->diffInDays($rentalEndDate), 1);

                // Calculate total price for the item
                $totalPrice = $price * $quantity * $days;

                // Add the new item to the invoice
                $invoice->additionalItems()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'rental_start_date' => $rentalStartDate,
                    'rental_end_date' => $rentalEndDate,
                ]);

                // Update the invoice total
                $invoice->total_amount += $totalPrice;
            }

            // Save the updated invoice
            $invoice->save();
            DB::commit();

            return redirect()->route('invoices.show', $invoiceId)->with('success', 'Items added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add items: ' . $e->getMessage());
        }
    }


    public function updatePaymentStatus(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'paid' => 'required|boolean',
        ]);

        // Update the paid status and dynamically set the status field
        $invoice->update([
            'paid' => $validated['paid'],
            'status' => $validated['paid'] ? 'active' : 'draft',
        ]);

        return redirect()->route('invoices.edit', $id)->with('success', 'Payment status updated successfully.');
    }

    public function updateInvoiceStatus(Request $request, $id)
    {
        // Fetch the invoice
        $invoice = Invoice::findOrFail($id);

        // Validate the request (if any additional fields are sent, e.g., reason for status change)
        $validated = $request->validate([
            'status' => 'required|in:returned,overdue', // Ensure status is valid enum
        ]);

        // Update the invoice status
        $invoice->status = $validated['status'];

        // Save the updated invoice
        $invoice->save();

        // Redirect back with success message
        return redirect()->route('invoices.edit', $id)->with('success', 'Invoice status updated successfully.');
    }


    public function returned(Request $request)
    {
        // Retrieve the selected category from the session, default to 'daily'
        $selectedCategory = $request->query('category', session('category', 'daily'));

        // Store the selected category in the session for persistence
        session(['category' => $selectedCategory]);

        // Fetch invoices with 'returned' status and category filter
        $invoices = Invoice::with('customer')
            ->whereHas('category', function ($query) use ($selectedCategory) {
                $query->where('name', $selectedCategory);
            })
            ->where('status', 'returned') // Only returned invoices
            ->paginate(10);

        // Pass the selected category to the view
        return view('invoices.returned', compact('invoices', 'selectedCategory'));
    }


    public function overdue(Request $request)
    {
        // Retrieve the selected category from the session, default to 'daily'
        $selectedCategory = $request->query('category', session('category', 'daily'));

        // Store the selected category in the session for persistence
        session(['category' => $selectedCategory]);

        // Fetch overdue invoices with category filter
        $invoices = Invoice::with('customer')
            ->whereHas('category', function ($query) use ($selectedCategory) {
                $query->where('name', $selectedCategory);
            })
            ->where('rental_end_date', '<', now()) // Rental period has ended
            ->where('paid', false) // Unpaid invoices only
            ->paginate(10);

        // Pass the selected category to the view
        return view('invoices.overdue', compact('invoices', 'selectedCategory'));
    }

}
