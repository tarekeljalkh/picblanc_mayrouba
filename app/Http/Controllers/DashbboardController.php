<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        // Fetch total counts for customers, invoices, and invoice statuses for the selected category
        $customersCount = Customer::count();
        $invoicesCount = Invoice::where('category_id', $category->id)->count();
        $totalPaid = Invoice::where('category_id', $category->id)->where('paid', true)->count();
        $totalUnpaid = Invoice::where('category_id', $category->id)->where('paid', false)->count();
        $notReturnedCount = Invoice::where('category_id', $category->id)->where('status', 'active')->count();
        $returnedCount = Invoice::where('category_id', $category->id)->where('status', 'returned')->count();
        $overdueCount = Invoice::where('category_id', $category->id)
            ->where('rental_end_date', '<', now())
            ->where('paid', false)
            ->count();

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
            'invoices',
            'categoryName'
        ));
    }

    public function trialBalance(Request $request)
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

        // Fetch total paid invoices (including additional items)
        $totalPaid = Invoice::where('category_id', $category->id)
            ->where('paid', true)
            ->whereBetween('created_at', [$from, $to])
            ->with('additionalItems')
            ->get()
            ->sum('total_amount');

        // Fetch total unpaid invoices (including additional items)
        $totalUnpaid = Invoice::where('category_id', $category->id)
            ->where('paid', false)
            ->whereBetween('created_at', [$from, $to])
            ->with('additionalItems')
            ->get()
            ->sum('total_amount');

        // Fetch total returned invoices (optional adjustment if required)
        $totalPaidCreditCard = Invoice::where('category_id', $category->id)
            ->where('paid', true)
            ->where('payment_method', '=', 'credit_card')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total_amount');

        $totalUnpaidCreditCard = Invoice::where('category_id', $category->id)
            ->where('paid', false)
            ->where('payment_method', '=', 'credit_card')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total_amount');


        // Combine all data for the table
        $trialBalanceData = [
            ['description' => 'Total Paid Invoices', 'amount' => $totalPaid],
            ['description' => 'Total Unpaid Invoices', 'amount' => $totalUnpaid],
            ['description' => 'Total Paid by Credit Card', 'amount' => $totalPaidCreditCard],
            ['description' => 'Total UnPaid by Credit Card', 'amount' => $totalUnpaidCreditCard],
        ];

        return view('trial-balance.index', compact(
            'trialBalanceData',
            'fromDate',
            'toDate'
        ));
    }


    // public function trialBalance(Request $request)
    // {
    //     // Set default "from" and "to" dates to today if not provided
    //     $fromDate = $request->input('from_date', Carbon::today()->toDateString());
    //     $toDate = $request->input('to_date', Carbon::today()->toDateString());

    //     // Parse the dates using Carbon
    //     $from = Carbon::parse($fromDate)->startOfDay();
    //     $to = Carbon::parse($toDate)->endOfDay();

    //     // Fetch the selected category from the session
    //     $categoryName = session('category', 'daily');
    //     $category = Category::where('name', $categoryName)->first();

    //     if (!$category) {
    //         return redirect()->back()->withErrors('Invalid category selected.');
    //     }

    //     // Fetch total income (paid invoices + returned invoices) within the date range and category
    //     $totalPaid = Invoice::where('category_id', $category->id)
    //         ->where('paid', true)
    //         ->whereBetween('created_at', [$from, $to])
    //         ->sum('total_amount');

    //     // Fetch total unpaid amount (unpaid invoices) within the date range and category
    //     $totalUnpaid = Invoice::where('category_id', $category->id)
    //         ->where('paid', false)
    //         ->whereBetween('created_at', [$from, $to])
    //         ->sum('total_amount');

    //     // Fetch total returned invoices
    //     $totalReturnedInvoices = Invoice::where('category_id', $category->id)
    //         ->where('status', 'returned')
    //         ->whereBetween('created_at', [$from, $to])
    //         ->sum('total_amount');

    //     // Combine all data for the table
    //     $trialBalanceData = [
    //         ['description' => 'Total Paid Invoices', 'amount' => $totalPaid],
    //         ['description' => 'Total Unpaid Invoices', 'amount' => $totalUnpaid],
    //         ['description' => 'Total Returned Invoices', 'amount' => $totalReturnedInvoices],
    //     ];

    //     return view('trial-balance.index', compact(
    //         'trialBalanceData',
    //         'fromDate',
    //         'toDate'
    //     ));
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
        $productSales = Product::with([
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

        // Calculate total income and other data from items
        $totalIncome = 0;
        $productBalances = [];

        foreach ($productSales as $product) {
            $totalQuantity = 0;
            $totalIncomeForProduct = 0;

            // Calculate totals for original invoice items
            foreach ($product->invoiceItems as $item) {
                $days = $item->days ?? 1; // Use 1 day if 'days' is null
                $totalQuantity += $item->quantity;
                $totalIncomeForProduct += $item->price * $item->quantity * $days;
            }

            // Calculate totals for additional items
            foreach ($product->additionalItems as $item) {
                $days = $item->days ?? 1; // Use 1 day if 'days' is null
                $totalQuantity += $item->quantity;
                $totalIncomeForProduct += $item->price * $item->quantity * $days;
            }

            $averagePrice = $totalQuantity > 0 ? $totalIncomeForProduct / $totalQuantity : 0;

            if ($totalQuantity > 0) {
                $productBalances[] = [
                    'product' => $product->name,
                    'quantity' => $totalQuantity,
                    'price_per_unit' => round($averagePrice, 2),
                    'total' => round($totalIncomeForProduct, 2),
                ];
                $totalIncome += $totalIncomeForProduct;
            }
        }

        // Pass totals and product balances to the view
        return view('trial-balance.products', compact('productBalances', 'totalIncome', 'fromDate', 'toDate'));
    }

}
