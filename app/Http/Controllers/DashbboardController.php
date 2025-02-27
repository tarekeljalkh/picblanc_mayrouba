<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
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

        $totalPaid = Invoice::where('category_id', $category->id)
            ->get() // Fetch all invoices for the category
            ->filter(fn($invoice) => $invoice->payment_status === 'fully_paid') // Filter fully paid invoices
            ->count();

        $totalPartiallyPaid = Invoice::where('category_id', $category->id)
            ->get() // Fetch all invoices for the category
            ->filter(fn($invoice) => $invoice->payment_status === 'partially_paid') // Filter fully paid invoices
            ->count();


        $totalUnpaid = Invoice::where('category_id', $category->id)
            ->get() // Fetch all invoices for the category
            ->filter(fn($invoice) => $invoice->payment_status === 'unpaid') // Filter unpaid invoices
            ->count();

        $notReturnedCount = Invoice::where('category_id', $category->id)
            ->with(['invoiceitems', 'additionalItems', 'customItems'])
            ->get() // Fetch all invoices matching the category
            ->filter(fn($invoice) => !$invoice->returned) // Only include invoices where `returned` is true
            ->count(); // Count the filtered invoices


        $returnedCount = Invoice::where('category_id', $category->id)
            ->with(['invoiceitems', 'additionalItems', 'customItems'])
            ->get() // Fetch all invoices matching the category
            ->filter(fn($invoice) => $invoice->returned) // Only include invoices where `returned` is true
            ->count(); // Count the filtered invoices

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
            'totalPartiallyPaid',
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
        $user = auth()->user(); // Get the authenticated user

        // Retrieve date range from the request or default to today's date
        $fromDate = $request->input('from_date', Carbon::today()->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        // Parse the dates for the start and end of the day
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        // Initialize totals
        $totalPaidInvoices = 0;
        $totalUnpaidInvoices = 0;
        $totalPaidByCreditCard = 0;

        // Fetch all payments within the date range
        $invoicePayments = InvoicePayment::whereBetween('payment_date', [$from, $to])
                                          ->get();

        // Loop through the payments and calculate totals
        foreach ($invoicePayments as $payment) {
            // Total paid amount for each invoice
            $paidAmount = $payment->amount;

            // Add to total paid invoices
            $totalPaidInvoices += $paidAmount;

            // If the payment method is credit card, add to the credit card total
            if ($payment->payment_method === 'credit_card') {
                $totalPaidByCreditCard += $paidAmount;
            }
        }

        // Fetch all invoices within the date range to calculate total unpaid invoices
        $invoices = Invoice::whereHas('payments', function ($query) use ($from, $to) {
                $query->whereBetween('payment_date', [$from, $to]);
            })
            ->when($user->role !== 'admin', function ($query) use ($user) {
                // Restrict to the authenticated user's invoices if not admin
                $query->where('user_id', $user->id);
            })
            ->get();

        // Calculate total unpaid invoices
        foreach ($invoices as $invoice) {
            $totals = $invoice->calculateTotals();

            // Retrieve final total and refund for unused days
            $finalTotal = $totals['finalTotal'] ?? 0;
            $refundForUnusedDays = $totals['refundForUnusedDays'] ?? 0;

            // Total paid amount (including deposit) from payments
            $paid = $invoice->payments->sum('amount') + $invoice->deposit;

            // Calculate unpaid amount considering refund and rounding issues
            $unpaid = max(0, round($finalTotal - $paid - $refundForUnusedDays, 2));

            // Update unpaid invoices total
            $totalUnpaidInvoices += $unpaid;
        }

        // Prepare trial balance data
        $trialBalanceData = [
            ['description' => 'Total Paid Invoices', 'amount' => $totalPaidInvoices],
            ['description' => 'Total Unpaid Invoices', 'amount' => $totalUnpaidInvoices],
            ['description' => 'Total Paid by Credit Card', 'amount' => $totalPaidByCreditCard],
        ];

        // Return the view with trial balance data
        return view('trial-balance.index', compact('trialBalanceData', 'fromDate', 'toDate'));
    }


    public function trialBalanceByProducts(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

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

        // Check if the category is "season"
        $isSeasonal = $category->name === 'season';

        // Fetch all products filtered by rental dates only (ignore payments)
        $products = Product::with([
            'invoiceItems' => function ($query) use ($from, $to, $category, $isSeasonal, $user) {
                $query->whereHas('invoice', function ($invoiceQuery) use ($category, $from, $to, $isSeasonal, $user) {
                    $invoiceQuery->where('category_id', $category->id);

                    if ($user->role !== 'admin') {
                        $invoiceQuery->where('user_id', $user->id);
                    }

                    if ($isSeasonal) {
                        // Filter by created_at for "season" category
                        $invoiceQuery->whereBetween('created_at', [$from, $to]);
                    } else {
                        // Filter by rental dates for non-seasonal categories
                        $invoiceQuery->where(function ($dateQuery) use ($from, $to) {
                            $dateQuery->whereBetween('rental_start_date', [$from, $to]) // product rented in this date range
                                ->orWhereBetween('rental_end_date', [$from, $to]) // or product rented until this range
                                ->orWhere(function ($spanQuery) use ($from, $to) {
                                    $spanQuery->where('rental_start_date', '<=', $to) // rental period started before or on the end date
                                        ->where('rental_end_date', '>=', $from); // and ended after or on the start date
                                });
                        });
                    }
                });
            },
            'additionalItems' => function ($query) use ($from, $to, $category, $isSeasonal, $user) {
                $query->whereHas('invoice', function ($invoiceQuery) use ($category, $from, $to, $isSeasonal, $user) {
                    $invoiceQuery->where('category_id', $category->id);

                    if ($user->role !== 'admin') {
                        $invoiceQuery->where('user_id', $user->id);
                    }

                    if ($isSeasonal) {
                        // Filter by created_at for "season" category
                        $invoiceQuery->whereBetween('created_at', [$from, $to]);
                    } else {
                        // Filter by rental dates for non-seasonal categories
                        $invoiceQuery->where(function ($dateQuery) use ($from, $to) {
                            $dateQuery->whereBetween('rental_start_date', [$from, $to]) // product rented in this date range
                                ->orWhereBetween('rental_end_date', [$from, $to]) // or product rented until this range
                                ->orWhere(function ($spanQuery) use ($from, $to) {
                                    $spanQuery->where('rental_start_date', '<=', $to) // rental period started before or on the end date
                                        ->where('rental_end_date', '>=', $from); // and ended after or on the start date
                                });
                        });
                    }
                });
            }
        ])->get();

        $productBalances = [];

        foreach ($products as $product) {
            $totalQuantity = 0;

            // Calculate totals for original invoice items (still considering the quantity)
            foreach ($product->invoiceItems as $item) {
                $remainingQuantity = $item->quantity - ($item->returned_quantity ?? 0);
                $totalQuantity += $remainingQuantity;
            }

            // Calculate totals for additional items (still considering the quantity)
            foreach ($product->additionalItems as $item) {
                $remainingQuantity = $item->quantity - ($item->returned_quantity ?? 0);
                $totalQuantity += $remainingQuantity;
            }

            // Add to productBalances only if there's a positive quantity
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
