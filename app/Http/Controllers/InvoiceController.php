<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Traits\FileUploadTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('customer')->get(); // Eager load customer data
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
        // Validate the input
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|required_without:customer_id|string|max:255',
            'customer_email' => 'nullable|email|max:255|required_without:customer_id',
            'customer_phone' => 'nullable|string|max:255|required_without:customer_id',
            'customer_address' => 'nullable|string|max:255|required_without:customer_id',
            'rental_start_date' => 'required|date', // Validate rental start date
            'rental_end_date' => 'required|date|after_or_equal:rental_start_date', // Validate rental end date
            'deposit_card' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Validate image upload
            'products' => 'required|array|min:1', // Ensure at least one product is selected
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'integer|min:1',
            'prices' => 'required|array|min:1',
            'prices.*' => 'numeric|min:0',
            'vat' => 'required|array|min:1',
            'vat.*' => 'numeric|min:0',
            'discount' => 'nullable|array',
            'discount.*' => 'numeric|min:0',
        ]);

        // Step 1: Handle Customer (Existing or New)
        if ($request->filled('customer_id')) {
            // Use the existing customer, no updates to the customer data
            $customer = Customer::findOrFail($request->customer_id);
        } else {
            // Create a new customer if no existing customer was selected
            $customer = new Customer();
            $customer->name = $request->customer_name;
            $customer->email = $request->customer_email;
            $customer->phone = $request->customer_phone;
            $customer->address = $request->customer_address;

            // Handle file upload for the deposit card
            if ($request->hasFile('deposit_card')) {
                $filePath = $this->uploadImage($request, 'deposit_card', null, '/uploads/customers');
                $customer->deposit_card = $filePath;
            }

            $customer->save();
        }

        // Step 2: Create the Invoice
        $invoice = new Invoice();
        $invoice->customer_id = $customer->id; // Link to the selected or newly created customer
        //$invoice->rental_start_date = $request->rental_start_date; // Set rental start date
        //$invoice->rental_end_date = $request->rental_end_date; // Set rental end date
        // Convert dates to the correct format
        $invoice->rental_start_date = Carbon::createFromFormat('d-m-Y', $request->rental_start_date)->format('Y-m-d');
        $invoice->rental_end_date = Carbon::createFromFormat('d-m-Y', $request->rental_end_date)->format('Y-m-d');

        $invoice->total = array_sum($request->total_price); // Calculate the total from the invoice items
        $invoice->paid = false; // Set the invoice as unpaid by default
        $invoice->status = 'active'; // Set initial status to active
        $invoice->save();

        // Step 3: Add Invoice Items and decrease stock
        foreach ($request->products as $index => $product_id) {
            // Create invoice items
            $invoice->items()->create([
                'product_id' => $product_id,
                'quantity' => $request->quantities[$index],
                'price' => $request->prices[$index],
                'vat' => $request->vat[$index],
                'discount' => $request->discount[$index] ?? 0,
                'total_price' => $this->calculateTotalPrice(
                    $request->quantities[$index],
                    $request->prices[$index],
                    $request->vat[$index],
                    $request->discount[$index] ?? 0
                ),
            ]);

            // Decrease product stock
            $product = Product::findOrFail($product_id);
            $product->decrement('stock', $request->quantities[$index]);
        }

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice created successfully');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with('items.product')->findOrFail($id);

        // Initialize totals
        $subtotal = 0;
        $discountTotal = 0;
        $vatTotal = 0;
        $total = 0;

        foreach ($invoice->items as $item) {
            $itemSubtotal = $item->quantity * $item->price;
            $itemVat = ($itemSubtotal * $item->vat) / 100;
            $itemDiscount = ($itemSubtotal * $item->discount) / 100;
            $itemTotal = $itemSubtotal + $itemVat - $itemDiscount;

            $subtotal += $itemSubtotal;
            $discountTotal += $itemDiscount;
            $vatTotal += $itemVat;
            $total += $itemTotal;
        }

        return view('invoices.show', compact('invoice', 'subtotal', 'discountTotal', 'vatTotal', 'total'));
    }

    /**
     * Print the invoice.
     */
    public function print($id)
    {
        $invoice = Invoice::with('items.product')->findOrFail($id);

        // Initialize totals
        $subtotal = 0;
        $discountTotal = 0;
        $vatTotal = 0;
        $total = 0;

        foreach ($invoice->items as $item) {
            $itemSubtotal = $item->quantity * $item->price;
            $itemVat = ($itemSubtotal * $item->vat) / 100;
            $itemDiscount = ($itemSubtotal * $item->discount) / 100;
            $itemTotal = $itemSubtotal + $itemVat - $itemDiscount;

            $subtotal += $itemSubtotal;
            $discountTotal += $itemDiscount;
            $vatTotal += $itemVat;
            $total += $itemTotal;
        }

        return view('invoices.print', compact('invoice', 'subtotal', 'discountTotal', 'vatTotal', 'total'));
    }

    /**
     * Download the invoice as PDF.
     */
    public function download($id)
    {
        // Fetch the invoice and its associated items
        $invoice = Invoice::with('items.product')->findOrFail($id);

        // Calculate totals (as in your current logic)
        $subtotal = 0;
        $discountTotal = 0;
        $vatTotal = 0;
        $total = 0;

        foreach ($invoice->items as $item) {
            $itemSubtotal = $item->quantity * $item->price;
            $itemVat = ($itemSubtotal * $item->vat) / 100;
            $itemDiscount = ($itemSubtotal * $item->discount) / 100;
            $itemTotal = $itemSubtotal + $itemVat - $itemDiscount;

            $subtotal += $itemSubtotal;
            $discountTotal += $itemDiscount;
            $vatTotal += $itemVat;
            $total += $itemTotal;
        }

        // Generate the PDF using the 'invoices.download' view
        $pdf = Pdf::loadView('invoices.download', compact('invoice', 'subtotal', 'discountTotal', 'vatTotal', 'total'));

        // Return the PDF download response
        return $pdf->download('invoice-' . $invoice->id . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the invoice, customers, and products for editing
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
            'prices' => 'required|array|min:1',
            'vat' => 'required|array|min:1',
            'discount' => 'nullable|array',
        ]);

        // Find the invoice
        $invoice = Invoice::findOrFail($id);

        // Update customer information
        $invoice->customer_id = $request->customer_id; // Ensure the correct customer is assigned
        $invoice->total = array_sum($request->total_price); // Recalculate the total based on items
        $invoice->save();

        // Step 2: Remove existing items and re-add updated ones
        $invoice->items()->delete();
        foreach ($request->products as $index => $product_id) {
            $invoice->items()->create([
                'product_id' => $product_id,
                'quantity' => $request->quantities[$index],
                'price' => $request->prices[$index],
                'vat' => $request->vat[$index],
                'discount' => $request->discount[$index] ?? 0,
                'total_price' => $this->calculateTotalPrice(
                    $request->quantities[$index],
                    $request->prices[$index],
                    $request->vat[$index],
                    $request->discount[$index] ?? 0
                ),
            ]);
        }

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

    /**
     * Calculate total price based on quantity, price, VAT, and discount.
     */
    private function calculateTotalPrice($quantity, $price, $vat, $discount = 0)
    {
        $subtotal = $quantity * $price;
        $vatAmount = ($subtotal * $vat) / 100;
        $discountAmount = ($subtotal * $discount) / 100;
        $total = $subtotal + $vatAmount - $discountAmount;

        return round($total, 2);
    }


    public function customer_store(Request $request)
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
        return redirect()->route('invoices.create')->with('new_customer_id', $customer->id);
    }
}
