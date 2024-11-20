<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $invoices = Invoice::with('customer')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        return view('invoices.index', compact('invoices', 'startDate', 'endDate'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
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
            'amount_per_day' => 'required|numeric',
            'days' => 'required|integer',
            'total_amount' => 'required|numeric',
            'note' => 'nullable|string|max:255',
        ]);

        $customer = $this->getOrCreateCustomer($request);

        $invoiceItems = $this->prepareInvoiceItems($request);

        $invoice = Invoice::create([
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

        $invoice->items()->saveMany($invoiceItems);

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice created successfully');
    }

    public function show($id)
    {
        $invoice = Invoice::with('items.product')->findOrFail($id);
        $totals = $this->calculateTotals($invoice->items, $invoice->total_vat, $invoice->total_discount);

        return view('invoices.show', array_merge(['invoice' => $invoice], $totals));
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        // $validatedData = $request->validate([
        //     'rental_start_date' => 'required|date',
        //     'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
        //     'products' => 'required|array|min:1',
        //     'products.*' => 'exists:products,id',
        //     'quantities' => 'required|array|min:1',
        //     'quantities.*' => 'integer|min:1',
        //     'prices' => 'required|array|min:1',
        //     'prices.*' => 'numeric|min:0',
        //     'return_quantities.*' => 'nullable|integer|min:0',
        //     'return_dates.*' => 'nullable|date|before_or_equal:rental_end_date',
        //     'total_vat' => 'nullable|numeric|min:0',
        //     'total_discount' => 'nullable|numeric|min:0',
        //     'paid' => 'required|in:0,1',
        //     'amount_per_day' => 'required|numeric',
        //     'days' => 'required|integer',
        //     'total_amount' => 'required|numeric',
        //     'note' => 'nullable|string|max:255',
        // ]);

        $invoice = Invoice::with('items')->findOrFail($id);
        $newItemIds = [];

        foreach ($request->products as $index => $product_id) {
            $quantity = $request->quantities[$index];
            $price = $request->prices[$index];
            $totalPrice = $quantity * $price;
            $returnQuantity = $request->return_quantities[$index] ?? 0;
            $returnDate = $request->return_dates[$index] ?? null;
            $reason = $request->reasons[$index] ?? null;

            if (!empty($request->existing_items[$index])) {
                $existingItem = $invoice->items()->find($request->existing_items[$index]);
                if ($existingItem) {
                    $newItemIds[] = $existingItem->id;
                    $previousQuantity = $existingItem->quantity;

                    if ($returnQuantity > 0) {
                        $this->logInvoiceHistory($existingItem, 'partial_return', $previousQuantity, $quantity, $existingItem->price, $price, $reason, $returnQuantity, $returnDate);
                    }

                    $existingItem->update([
                        'quantity' => $quantity,
                        'price' => $price,
                        'total_price' => $totalPrice,
                    ]);
                }
            } else {
                $newItem = $invoice->items()->create([
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                ]);

                $this->logInvoiceHistory($newItem, 'add', null, $quantity, null, $price, $reason);
            }
        }

        if ($request->filled('removed_items')) {
            foreach ($request->input('removed_items') as $removedItemId) {
                $removedItem = $invoice->items()->find($removedItemId);
                if ($removedItem) {
                    $this->logInvoiceHistory($removedItem, 'remove', $removedItem->quantity, null, $removedItem->price, null, 'Item removed');
                    $removedItem->delete();
                }
            }
        }

        $totals = $this->calculateTotals($invoice->items, $request->total_vat, $request->total_discount);
        $invoice->update([
            'total_amount' => $totals['total'],
            'total_vat' => $request->total_vat ?? 0,
            'total_discount' => $request->total_discount ?? 0,
            'paid' => $request->paid,
            'note' => $request->note,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully');
    }


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

    private function validateInvoice(Request $request, $isCreate = true)
    {
        return $request->validate([
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
            'amount_per_day' => 'required|numeric',
            'days' => 'required|integer',
            'total_amount' => 'required|numeric',
            'note' => 'nullable|string|max:255',
        ]);
    }

    private function getOrCreateCustomer(Request $request)
    {
        if ($request->filled('customer_id')) {
            return Customer::findOrFail($request->customer_id);
        }
        return Customer::create([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'address' => $request->customer_address,
        ]);
    }

    private function prepareInvoiceItems(Request $request)
    {
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
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
                'days' => $request->days,
            ]);
        }
        return $invoiceItems;
    }

    private function logInvoiceHistory($item, $action, $previousQuantity, $newQuantity, $previousPrice, $newPrice, $reason = null)
    {
        InvoiceItemHistory::create([
            'invoice_item_id' => $item->id,
            'invoice_id' => $item->invoice_id,
            'product_id' => $item->product_id,
            'action' => $action,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $newQuantity,
            'previous_price' => $previousPrice,
            'new_price' => $newPrice,
            'change_reason' => $reason,
        ]);
    }

    private function calculateTotals($items, $vatPercentage, $discountPercentage)
    {
        $subtotal = $items->sum(fn($item) => $item->quantity * $item->price);
        $vatAmount = ($subtotal * $vatPercentage) / 100;
        $discountAmount = ($subtotal * $discountPercentage) / 100;
        $total = $subtotal + $vatAmount - $discountAmount;

        return compact('subtotal', 'vatAmount', 'discountAmount', 'total');
    }
}
