<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashbboardController extends Controller
{
    public function index()
    {
        // Fetch total clients, invoices, paid and unpaid totals
        $customersCount = Customer::count();
        $invoicesCount = Invoice::count();
        $totalPaid = Invoice::where('paid', true)->count();
        $totalUnpaid = Invoice::where('paid', false)->count();

        // Fetch latest invoices (for the table)
        $invoices = Invoice::with('customer')->orderBy('created_at', 'desc')->paginate(10);

        // Pass the data to the view
        return view('dashboard', compact('customersCount', 'invoicesCount', 'totalPaid', 'totalUnpaid', 'invoices'));
    }

    public function trialBalance(Request $request)
    {
        // Set default "from" and "to" dates to today if not provided
        $fromDate = $request->input('from_date', Carbon::today()->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        // Parse the dates using Carbon
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        // Fetch total income (paid invoices) within the date range
        $totalIncome = Invoice::where('paid', true)
            ->whereBetween('created_at', [$from, $to])
            ->sum('total_amount');

        // Fetch total unpaid amount (unpaid invoices) within the date range
        $totalUnpaid = Invoice::where('paid', false)
            ->whereBetween('created_at', [$from, $to])
            ->sum('total_amount');

        // Fetch additional costs from additionalItems within the date range
        $additionalCosts = Invoice::whereBetween('created_at', [$from, $to])
            ->with('additionalItems')
            ->get()
            ->sum(fn($invoice) => $invoice->additionalItems->sum('total_price'));

        // Fetch returned costs from returnDetails within the date range
        $returnedCosts = Invoice::whereBetween('created_at', [$from, $to])
            ->with('returnDetails')
            ->get()
            ->sum(fn($invoice) => $invoice->returnDetails->sum('cost'));

        // Calculate net income
        $netIncome = $totalIncome - $returnedCosts + $additionalCosts;

        // Pass the data and date range to the view
        return view('trial-balance.index', compact(
            'totalIncome',
            'totalUnpaid',
            'additionalCosts',
            'returnedCosts',
            'netIncome',
            'fromDate',
            'toDate'
        ));
    }


    public function trialBalanceByProducts(Request $request)
    {
        // Set default "from" and "to" dates to today if not provided
        $fromDate = $request->input('from_date', Carbon::today()->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        // Parse the dates using Carbon
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        // Fetch product sales data within the date range
        $productSales = Product::with(['invoiceItems' => function ($query) use ($from, $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }])->get();

        // Calculate total income and other data from product sales
        $totalIncome = 0;
        $productBalances = [];

        foreach ($productSales as $product) {
            $totalQuantity = $product->invoiceItems->sum('quantity');
            $totalIncomeForProduct = $product->invoiceItems->sum(fn($item) => $item->quantity * $item->price);
            $averagePrice = $totalQuantity > 0 ? $totalIncomeForProduct / $totalQuantity : 0;

            $productBalances[] = [
                'product' => $product->name,
                'quantity' => $totalQuantity,
                'price_per_unit' => $averagePrice,
                'total' => $totalIncomeForProduct,
            ];
            $totalIncome += $totalIncomeForProduct;
        }

        return view('trial-balance.products', compact('productBalances', 'totalIncome', 'fromDate', 'toDate'));
    }


}
