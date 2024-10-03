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
        $clientsCount = Customer::count();
        $invoicesCount = Invoice::count();
        $totalPaid = Invoice::where('paid', true)->sum('total');
        $totalUnpaid = Invoice::where('paid', false)->sum('total');

        // Fetch latest invoices (for the table)
        $invoices = Invoice::with('customer')->orderBy('created_at', 'desc')->get();

        // Pass the data to the view
        return view('dashboard', compact('clientsCount', 'invoicesCount', 'totalPaid', 'totalUnpaid', 'invoices'));
    }

    public function trialBalance(Request $request)
    {
        // Get the "from" and "to" dates from the request or default to today
        $fromDate = $request->input('from_date', Carbon::today()->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        // Parse the dates to Carbon instances
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        // Filter total income (paid invoices) within the date range
        $totalIncome = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');

        // Filter total unpaid amount within the date range
        $totalUnpaid = Invoice::where('status', 'unpaid')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');

        // Assuming you have expenses, filter total expenses within the date range
        $totalExpenses = 0; // Example: $totalExpenses = Expense::whereBetween('created_at', [$from, $to])->sum('amount');

        // Outstanding balances (unpaid invoices) within the date range
        $outstandingBalances = Invoice::where('status', 'unpaid')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total') - Invoice::where('status', 'unpaid')
            ->whereBetween('created_at', [$from, $to])
            ->sum('paid');

        // Calculate net income
        $netIncome = $totalIncome - $totalExpenses;

        // Pass the data and date range to the view
        return view('trial-balance.index', compact('totalIncome', 'totalUnpaid', 'totalExpenses', 'outstandingBalances', 'netIncome', 'fromDate', 'toDate'));
    }

}
