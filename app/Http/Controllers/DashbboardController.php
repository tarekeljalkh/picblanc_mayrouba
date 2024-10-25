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
        $invoices = Invoice::with('customer')->orderBy('created_at', 'desc')->get();

        // Pass the data to the view
        return view('dashboard', compact('customersCount', 'invoicesCount', 'totalPaid', 'totalUnpaid', 'invoices'));
    }

    public function trialBalance(Request $request)
    {
        // Set the default "from" date to the beginning of time (0000-01-01) and "to" date to today
        $fromDate = $request->input('from_date', Carbon::createFromFormat('Y-m-d', '0000-01-01')->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        // Parse the dates using Carbon
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        // Fetch total income (paid invoices) within the date range
        $totalIncome = Invoice::where('paid', true)
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');

        // Fetch total unpaid amount (unpaid invoices) within the date range
        $totalUnpaid = Invoice::where('paid', false)
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');

        // Assuming you have expenses model, filter total expenses within the date range
        // For now, we're setting $totalExpenses to 0 if no expenses are tracked.
        $totalExpenses = 0;

        // Outstanding balances are essentially unpaid invoices
        $outstandingBalances = $totalUnpaid;

        // Calculate net income (Total Income - Total Expenses)
        $netIncome = $totalIncome - $totalExpenses;

        // Pass the data and date range to the view
        return view('trial-balance.index', compact('totalIncome', 'totalUnpaid', 'totalExpenses', 'outstandingBalances', 'netIncome', 'fromDate', 'toDate'));
    }



}
