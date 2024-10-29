<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('customer')->get();
        return view('invoices.index', compact('invoices'));
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
            'total_vat' => 'nullable|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'paid' => 'required|in:0,1',
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

        // Calculate and add Invoice Items
        $invoiceItems = [];
        foreach ($request->products as $index => $product_id) {
            $quantity = $request->quantities[$index];
            $price = $request->prices[$index];
            $totalPrice = $quantity * $price;

            $invoiceItems[] = new InvoiceItem([
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
            ]);
        }

        // Create the Invoice using values directly from the form
        $invoice = new Invoice([
            'customer_id' => $customer->id,
            'rental_start_date' => $request->rental_start_date,
            'rental_end_date' => $request->rental_end_date,
            'total_vat' => $request->total_vat ?? 0,
            'total_discount' => $request->total_discount ?? 0,
            'amount_per_day' => $request->amount_per_day,
            'total_amount' => $request->total_amount,
            'paid' => $request->paid,
            'status' => 'active',
            'days' => $request->days,
        ]);
        $invoice->save();

        // Attach items to the invoice
        $invoice->items()->saveMany($invoiceItems);

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice created successfully');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with('items.product')->findOrFail($id);

        // Calculate subtotal, VAT, discount, and total
        $subtotal = $invoice->items->sum(fn($item) => $item->quantity * $item->price);
        $vatTotal = ($subtotal * $invoice->total_vat) / 100;
        $discountTotal = ($subtotal * $invoice->total_discount) / 100;
        $total = $subtotal + $vatTotal - $discountTotal;

        return view('invoices.show', compact('invoice', 'subtotal', 'vatTotal', 'discountTotal', 'total'));
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
            'total_vat' => 'nullable|numeric|min:0',
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
        $invoice->total_vat = $request->total_vat ?? 0;
        $invoice->total_discount = $request->total_discount ?? 0;
        $invoice->paid = (bool) $request->paid;
        $invoice->status = 'active';

        // Recalculate subtotal, VAT, discount, and total for updated items
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

        // Calculate total with VAT and discount
        $vatAmount = ($subtotal * $invoice->total_vat) / 100;
        $discountAmount = ($subtotal * $invoice->total_discount) / 100;
        $invoice->total = $subtotal + $vatAmount - $discountAmount;
        $invoice->save();

        // Attach updated items to the invoice
        $invoice->items()->saveMany($invoiceItems);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }
}
