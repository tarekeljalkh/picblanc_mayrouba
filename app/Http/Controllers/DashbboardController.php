<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashbboardController extends Controller
{
    public function index()
    {
        // Retrieve the selected category from the session, default to 'daily'
        $categoryName = session('category', 'daily');

        // Fetch the category based on the name
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            // Handle the case where the category doesn't exist
            abort(404, 'Category not found.');
        }

        // Fetch total counts for customers, invoices, and statuses for the selected category
        $customersCount = Customer::count();
        $invoicesCount = Invoice::where('category_id', $category->id)->count();

        // Use paid_amount and total_amount to determine statuses
        $totalPaid = Invoice::where('category_id', $category->id)
            ->whereColumn('paid_amount', '>=', 'total_amount') // Fully paid invoices
            ->count();

        $totalUnpaid = Invoice::where('category_id', $category->id)
            ->where('paid_amount', 0) // Unpaid invoices
            ->count();

        $notReturnedCount = Invoice::where('category_id', $category->id)
            ->where('status', 'active')
            ->count();

        $returnedCount = Invoice::where('category_id', $category->id)
            ->where('status', 'returned')
            ->count();

        $overdueCount = Invoice::where('category_id', $category->id)
            ->where('rental_end_date', '<', now())
            ->whereColumn('paid_amount', '<', 'total_amount') // Not fully paid
            ->count();

        // Calculate revenue-related metrics
        $totalRevenue = Invoice::where('category_id', $category->id)
            ->sum('paid_amount'); // Total payments received

        $overdueRevenue = Invoice::where('category_id', $category->id)
            ->where('rental_end_date', '<', now())
            ->whereColumn('paid_amount', '<', 'total_amount') // Not fully paid
            ->sum(DB::raw('total_amount - paid_amount')); // Outstanding amount

        // Fetch latest invoices for the selected category
        $invoices = Invoice::with('customer')
            ->where('category_id', $category->id)
            ->where('status', '!=', 'returned')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Pass the data to the view
        return view('dashboard', compact(
            'customersCount',
            'invoicesCount',
            'totalPaid',
            'totalUnpaid',
            'notReturnedCount',
            'returnedCount',
            'overdueCount',
            'totalRevenue',
            'overdueRevenue',
            'invoices',
            'categoryName'
        ));
    }

    public function trialBalance(Request $request)
    {
        // Retrieve date range from the request or default to today's date
        $fromDate = $request->input('from_date', Carbon::today()->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        // Parse the date range
        try {
            $from = Carbon::parse($fromDate)->startOfDay();
            $to = Carbon::parse($toDate)->endOfDay();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Invalid date format.');
        }

        // Get the selected category
        $categoryName = session('category', 'daily');
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return redirect()->back()->withErrors('Invalid category selected.');
        }

        // Fetch invoices for the specified category and date range
        $invoices = Invoice::where('category_id', $category->id)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        if ($invoices->isEmpty()) {
            return redirect()->back()->with('info', 'No invoices found for the selected date range.');
        }

        // Initialize totals
        $totalPaidInvoices = 0;
        $totalUnpaidInvoices = 0;
        $totalPaidByCreditCard = 0;

        foreach ($invoices as $invoice) {
            // Calculate totals for regular and custom items
            $regularItemsTotal = $invoice->items->sum('total_price') +
                $invoice->customItems->sum(function ($customItem) {
                    return $customItem->price * $customItem->quantity;
                });

            $additionalItemsTotal = $invoice->additionalItems->sum(function ($additionalItem) {
                return $additionalItem->price * $additionalItem->quantity;
            });

            // Calculate discounted total for regular items only
            $discountAmount = ($regularItemsTotal * ($invoice->total_discount ?? 0)) / 100;
            $totalAmount = ($regularItemsTotal - $discountAmount) + $additionalItemsTotal;

            // Refunds are excluded here by simply omitting any refund-related logic

            // Paid and unpaid amounts
            $paid = $invoice->deposit + $invoice->paid_amount; // Total amount paid
            $unpaid = max(0, $totalAmount - $paid); // Remaining balance

            $totalPaidInvoices += $paid;
            $totalUnpaidInvoices += $unpaid;

            // Track credit card payments
            if ($invoice->payment_method === 'credit_card') {
                $totalPaidByCreditCard += $paid;
            }
        }

        // Prepare trial balance data
        $trialBalanceData = [
            ['description' => 'Total Paid Invoices', 'amount' => $totalPaidInvoices],
            ['description' => 'Total Unpaid Invoices', 'amount' => $totalUnpaidInvoices],
            ['description' => 'Total Paid by Credit Card', 'amount' => $totalPaidByCreditCard],
        ];

        return view('trial-balance.index', compact('trialBalanceData', 'fromDate', 'toDate'));
    }



    //with returned cost
    //     public function trialBalance(Request $request)
    // {
    //     // Retrieve date range from the request or default to today's date
    //     $fromDate = $request->input('from_date', Carbon::today()->toDateString());
    //     $toDate = $request->input('to_date', Carbon::today()->toDateString());

    //     // Parse the date range
    //     $from = Carbon::parse($fromDate)->startOfDay();
    //     $to = Carbon::parse($toDate)->endOfDay();

    //     // Get the selected category
    //     $categoryName = session('category', 'daily');
    //     $category = Category::where('name', $categoryName)->first();

    //     if (!$category) {
    //         return redirect()->back()->withErrors('Invalid category selected.');
    //     }

    //     // Fetch invoices for the specified category and date range
    //     $invoices = Invoice::where('category_id', $category->id)
    //         ->whereBetween('created_at', [$from, $to])
    //         ->get();

    //     // Initialize totals
    //     $totalPaidInvoices = 0;
    //     $totalUnpaidInvoices = 0;
    //     $totalPaidByCreditCard = 0;
    //     $totalReturnedCost = 0; // New: Initialize total returned cost

    //     foreach ($invoices as $invoice) {
    //         $paid = $invoice->deposit + $invoice->paid_amount; // Total amount paid (deposit + actual payments)
    //         $unpaid = max(0, $invoice->total_price - $paid);  // Remaining balance

    //         $totalPaidInvoices += $paid;
    //         $totalUnpaidInvoices += $unpaid;

    //         if ($invoice->payment_method === 'credit_card') {
    //             $totalPaidByCreditCard += $paid;
    //         }

    //         // Calculate the total returned cost for the invoice
    //         $totalReturnedCost += $invoice->returnDetails->sum('cost');
    //     }

    //     // Prepare trial balance data
    //     $trialBalanceData = [
    //         ['description' => 'Total Paid Invoices', 'amount' => $totalPaidInvoices],
    //         ['description' => 'Total Unpaid Invoices', 'amount' => $totalUnpaidInvoices],
    //         ['description' => 'Total Paid by Credit Card', 'amount' => $totalPaidByCreditCard],
    //         ['description' => 'Total Returned Cost', 'amount' => $totalReturnedCost], // Add returned cost to the report
    //     ];

    //     return view('trial-balance.index', compact('trialBalanceData', 'fromDate', 'toDate'));
    // }



    public function trialBalanceByProducts(Request $request)
    {
        // Set default "from" and "to" dates to today if not provided
        $fromDate = $request->input('from_date', Carbon::today()->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        // Parse the dates using Carbon
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        // Fetch the selected category from the session
        $categoryName = session('category', 'daily');
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return redirect()->back()->withErrors('Invalid category selected.');
        }

        // Fetch all products with invoice items and additional items
        $products = Product::with([
            'invoiceItems' => function ($query) use ($from, $to, $category) {
                $query->whereBetween('created_at', [$from, $to])
                    ->whereHas('invoice', function ($invoiceQuery) use ($category) {
                        $invoiceQuery->where('category_id', $category->id);
                    });
            },
            'additionalItems' => function ($query) use ($from, $to, $category) {
                $query->whereBetween('created_at', [$from, $to])
                    ->whereHas('invoice', function ($invoiceQuery) use ($category) {
                        $invoiceQuery->where('category_id', $category->id);
                    });
            }
        ])->get();

        $productBalances = [];

        foreach ($products as $product) {
            $totalQuantity = 0;

            // Calculate totals for original invoice items
            foreach ($product->invoiceItems as $item) {
                $remainingQuantity = $item->quantity - $item->returned_quantity;

                // Only factor in days for daily rentals
                $effectiveQuantity = session('category') === 'daily' ? $remainingQuantity * ($item->days ?? 1) : $remainingQuantity;

                $totalQuantity += $remainingQuantity;
            }

            // Calculate totals for additional items
            foreach ($product->additionalItems as $item) {
                $remainingQuantity = $item->quantity - $item->returned_quantity;

                // Only factor in days for daily rentals
                $effectiveQuantity = session('category') === 'daily' ? $remainingQuantity * ($item->days ?? 1) : $remainingQuantity;

                $totalQuantity += $remainingQuantity;
            }

            if ($totalQuantity > 0) {
                $productBalances[] = [
                    'product' => $product->name,
                    'quantity' => $totalQuantity,
                ];
            }
        }

        // Return the view with only the quantities of rented products
        return view('trial-balance.products', compact('productBalances', 'fromDate', 'toDate'));
    }
}
