<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemHistory;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the start and end dates from the request, defaulting to today's date if not provided
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Query invoices within the date range
        $invoices = Invoice::with('customer')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        return view('invoices.index', compact('invoices', 'startDate', 'endDate'));
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
            'total_amount' => 'required|numeric', // Validating total from the form
            'note' => 'nullable|string|max:255',
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
            'note' => $request->note,
            'user_id' => auth()->id(),
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
        // Validate the request
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'integer|min:1',
            'prices' => 'required|array|min:1',
            'prices.*' => 'numeric|min:0',
            'total_vat' => 'nullable|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'paid' => 'required|in:0,1',
            'note' => 'nullable|string|max:255',
        ]);

        $invoice = Invoice::with('items')->findOrFail($id);
        $newItemIds = [];

        // Handle additions and updates
        foreach ($request->products as $index => $product_id) {
            $quantity = $request->quantities[$index];
            $price = $request->prices[$index];
            $totalPrice = $quantity * $price;

            if (!empty($request->existing_items[$index])) {
                // Update existing item
                $existingItem = $invoice->items()->find($request->existing_items[$index]);
                if ($existingItem) {
                    $newItemIds[] = $existingItem->id;
                    $previousQuantity = $existingItem->quantity;
                    $previousPrice = $existingItem->price;

                    // Update the item
                    $existingItem->update([
                        'quantity' => $quantity,
                        'price' => $price,
                        'total_price' => $totalPrice,
                    ]);

                    // Log changes if values have changed
                    if ($previousQuantity != $quantity || $previousPrice != $price) {
                        \App\Models\InvoiceItemHistory::create([
                            'invoice_item_id' => $existingItem->id,
                            'invoice_id' => $invoice->id,
                            'product_id' => $product_id,
                            'action' => 'update',
                            'previous_quantity' => $previousQuantity,
                            'new_quantity' => $quantity,
                            'previous_price' => $previousPrice,
                            'new_price' => $price,
                            'change_reason' => $request->reasons[$index] ?? null,
                        ]);
                    }
                }
            } else {
                // Add new item
                $newItem = $invoice->items()->create([
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                ]);

                // Log new addition
                \App\Models\InvoiceItemHistory::create([
                    'invoice_item_id' => $newItem->id,
                    'invoice_id' => $invoice->id,
                    'product_id' => $product_id,
                    'action' => 'add',
                    'new_quantity' => $quantity,
                    'new_price' => $price,
                    'change_reason' => $request->reasons[$index] ?? null,
                ]);
            }
        }

        // Handle removals
        if ($request->filled('removed_items')) {
            //dd($request->input('removed_items'));
            foreach ($request->input('removed_items') as $removedItemId) {
                $removedItem = $invoice->items()->find($removedItemId);

                if ($removedItem) {
                    try {
                        // Log removal
                        \App\Models\InvoiceItemHistory::create([
                            'invoice_item_id' => $removedItem->id,
                            'invoice_id' => $invoice->id,
                            'product_id' => $removedItem->product_id,
                            'action' => 'remove',
                            'previous_quantity' => $removedItem->quantity,
                            'previous_price' => $removedItem->price,
                            'change_reason' => 'Item removed',
                        ]);

                        // Delete the item
                        $removedItem->delete();
                    } catch (\Exception $e) {
                        \Log::error('Failed to remove item with ID ' . $removedItemId . ': ' . $e->getMessage());
                    }
                } else {
                    \Log::warning("Item with ID {$removedItemId} not found for removal.");
                }
            }
        }

        // Update invoice totals
        $subtotal = $invoice->items->sum(fn($item) => $item->total_price);
        $vatAmount = ($subtotal * $request->total_vat) / 100;
        $discountAmount = ($subtotal * $request->total_discount) / 100;
        $invoice->update([
            'total_amount' => $subtotal + $vatAmount - $discountAmount,
            'total_vat' => $request->total_vat ?? 0,
            'total_discount' => $request->total_discount ?? 0,
            'paid' => $request->paid,
            'note' => $request->note,
        ]);

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


}
