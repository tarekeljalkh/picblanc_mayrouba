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
            abort(404, 'Category not found.');
        }

        // Fetch total counts
        $customersCount = Customer::count();
        $invoicesCount = Invoice::where('category_id', $category->id)->count();

        // ✅ Load invoices with payments
        // $invoices = Invoice::where('category_id', $category->id)->with('payments')->get();
        $invoices = Invoice::where('category_id', $category->id)
            ->with([
                'customer',
                'category',
                'invoiceItems',
                'customItems.invoice',          // in case you reference customItem->invoice
                'additionalItems.invoice',      // same here
                'payments',
                'returnDetails.invoiceItem',
                'returnDetails.additionalItem',
                'returnDetails.customItem',
            ])
            ->where('category_id', $category->id)
            ->get();

        // ✅ Use payment_status accessor for accurate classification
        $totalPaid = $invoices->filter(fn($invoice) => $invoice->payment_status === 'fully_paid')->count();
        $totalPartiallyPaid = $invoices->filter(fn($invoice) => $invoice->payment_status === 'partially_paid')->count();
        $totalUnpaid = $invoices->filter(fn($invoice) => $invoice->payment_status === 'unpaid')->count();

        // Not Returned
        $notReturnedCount = Invoice::where('category_id', $category->id)
            ->where(function ($query) {
                $query->whereHas('invoiceItems', fn($q) => $q->whereColumn('quantity', '>', 'returned_quantity'))
                    ->orWhereHas('additionalItems', fn($q) => $q->whereColumn('quantity', '>', 'returned_quantity'))
                    ->orWhereHas('customItems', fn($q) => $q->whereColumn('quantity', '>', 'returned_quantity'));
            })
            ->distinct()
            ->count();

        // Returned
        $returnedCount = Invoice::where('category_id', $category->id)
            ->whereDoesntHave('invoiceItems', fn($query) => $query->whereColumn('quantity', '>', 'returned_quantity'))
            ->whereDoesntHave('additionalItems', fn($query) => $query->whereColumn('quantity', '>', 'returned_quantity'))
            ->whereDoesntHave('customItems', fn($query) => $query->whereColumn('quantity', '>', 'returned_quantity'))
            ->count();

        // Overdue Count
        $overdueCount = $invoices->filter(function ($invoice) {
            $totals = $invoice->calculateTotals();
            $paid = round($invoice->payments->sum('amount'), 2);
            $due = round($totals['finalTotal'] ?? 0, 2);
            return $invoice->rental_end_date < now() && ($due - $paid) > 1.00;
        })->count();

        // Total revenue
        $totalRevenue = InvoicePayment::whereHas('invoice', fn($query) => $query->where('category_id', $category->id))->sum('amount');

        // Overdue revenue
        $overdueRevenue = $invoices->sum(function ($invoice) {
            $totals = $invoice->calculateTotals();
            $paid = round($invoice->payments->sum('amount'), 2);
            $due = round($totals['finalTotal'] ?? 0, 2);
            return max(0, $due - $paid);
        });

        // Paginate latest invoices
        $invoices = Invoice::with('customer')
            ->where('category_id', $category->id)
            ->where('status', '!=', 'returned')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
        $user = auth()->user();
        $selectedCategory = session('category', 'daily');

        $fromDate = $request->input('from_date', Carbon::today()->toDateString());
        $toDate = $request->input('to_date', Carbon::today()->toDateString());

        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        $totalPaidByCash = 0;
        $totalPaidByCreditCard = 0;
        $totalUnpaidInvoices = 0;

        // ✅ Fetch Payments by Category + Date Range
        $invoicePayments = InvoicePayment::whereHas('invoice', function ($query) use ($selectedCategory, $from, $to) {
            $query->whereHas('category', fn($q) => $q->where('name', $selectedCategory));

            if ($selectedCategory === 'season') {
                $query->whereBetween('created_at', [$from, $to]);
            } else {
                $query->where('rental_start_date', '>=', $from)
                    ->where('rental_end_date', '<=', $to);
            }
        })->get();

        // ✅ Sum payments by method
        foreach ($invoicePayments as $payment) {
            if ($payment->payment_method === 'cash') {
                $totalPaidByCash += $payment->amount;
            } elseif ($payment->payment_method === 'credit_card') {
                $totalPaidByCreditCard += $payment->amount;
            }
        }

        // ✅ Fetch invoices by Category + Date Range
        $invoices = Invoice::with('payments')->whereHas('category', fn($q) => $q->where('name', $selectedCategory))
            ->when($selectedCategory === 'season', function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, $to]);
            }, function ($query) use ($from, $to) {
                $query->where('rental_start_date', '>=', $from)
                    ->where('rental_end_date', '<=', $to);
            })
            ->get();

        // ✅ Calculate unpaid balances from model method
        foreach ($invoices as $invoice) {
            $totals = $invoice->calculateTotals();
            $totalUnpaidInvoices += $totals['balanceDue'] ?? 0;
        }

        // ✅ Trial balance summary
        $trialBalanceData = [
            ['description' => 'Total Paid Invoices (Cash)', 'amount' => round($totalPaidByCash, 2)],
            ['description' => 'Total Unpaid Invoices', 'amount' => round($totalUnpaidInvoices, 2)],
            ['description' => 'Total Paid by Credit Card', 'amount' => round($totalPaidByCreditCard, 2)],
        ];

        return view('trial-balance.index', compact('trialBalanceData', 'fromDate', 'toDate', 'selectedCategory'));
    }


    // public function trialBalance(Request $request)
    // {
    //     $user = auth()->user(); // Get the authenticated user

    //     // Retrieve date range from the request or default to today's date
    //     $fromDate = $request->input('from_date', Carbon::today()->toDateString());
    //     $toDate = $request->input('to_date', Carbon::today()->toDateString());

    //     // Parse the dates for the start and end of the day
    //     $from = Carbon::parse($fromDate)->startOfDay();
    //     $to = Carbon::parse($toDate)->endOfDay();

    //     // Initialize totals
    //     $totalPaidInvoices = 0;
    //     $totalUnpaidInvoices = 0;
    //     $totalPaidByCreditCard = 0;

    //     // Fetch all payments within the date range
    //     $invoicePayments = InvoicePayment::whereBetween('payment_date', [$from, $to])
    //                                       ->get();

    //     // Loop through the payments and calculate totals
    //     foreach ($invoicePayments as $payment) {
    //         // Total paid amount for each invoice
    //         $paidAmount = $payment->amount;

    //         // Add to total paid invoices
    //         $totalPaidInvoices += $paidAmount;

    //         // If the payment method is credit card, add to the credit card total
    //         if ($payment->payment_method === 'credit_card') {
    //             $totalPaidByCreditCard += $paidAmount;
    //         }
    //     }

    //     // Fetch all invoices within the date range to calculate total unpaid invoices
    //     $invoices = Invoice::whereHas('payments', function ($query) use ($from, $to) {
    //             $query->whereBetween('payment_date', [$from, $to]);
    //         })
    //         ->when($user->role !== 'admin', function ($query) use ($user) {
    //             // Restrict to the authenticated user's invoices if not admin
    //             $query->where('user_id', $user->id);
    //         })
    //         ->get();

    //     // Calculate total unpaid invoices
    //     foreach ($invoices as $invoice) {
    //         $totals = $invoice->calculateTotals();

    //         // Retrieve final total and refund for unused days
    //         $finalTotal = $totals['finalTotal'] ?? 0;
    //         $refundForUnusedDays = $totals['refundForUnusedDays'] ?? 0;

    //         // Total paid amount (including deposit) from payments
    //         $paid = $invoice->payments->sum('amount') + $invoice->deposit;

    //         // Calculate unpaid amount considering refund and rounding issues
    //         $unpaid = max(0, round($finalTotal - $paid - $refundForUnusedDays, 2));

    //         // Update unpaid invoices total
    //         $totalUnpaidInvoices += $unpaid;
    //     }

    //     // Prepare trial balance data
    //     $trialBalanceData = [
    //         ['description' => 'Total Paid Invoices', 'amount' => $totalPaidInvoices],
    //         ['description' => 'Total Unpaid Invoices', 'amount' => $totalUnpaidInvoices],
    //         ['description' => 'Total Paid by Credit Card', 'amount' => $totalPaidByCreditCard],
    //     ];

    //     // Return the view with trial balance data
    //     return view('trial-balance.index', compact('trialBalanceData', 'fromDate', 'toDate'));
    // }


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
                        $invoiceQuery->whereBetween('created_at', [$from, $to]);
                    } else {
                        $invoiceQuery->where(function ($dateQuery) use ($from, $to) {
                            $dateQuery->whereBetween('rental_start_date', [$from, $to])
                                ->orWhereBetween('rental_end_date', [$from, $to])
                                ->orWhere(function ($spanQuery) use ($from, $to) {
                                    $spanQuery->where('rental_start_date', '<=', $to)
                                        ->where('rental_end_date', '>=', $from);
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
                        $invoiceQuery->whereBetween('created_at', [$from, $to]);
                    } else {
                        $invoiceQuery->where(function ($dateQuery) use ($from, $to) {
                            $dateQuery->whereBetween('rental_start_date', [$from, $to])
                                ->orWhereBetween('rental_end_date', [$from, $to])
                                ->orWhere(function ($spanQuery) use ($from, $to) {
                                    $spanQuery->where('rental_start_date', '<=', $to)
                                        ->where('rental_end_date', '>=', $from);
                                });
                        });
                    }
                });
            }
        ])->get();

        // Fetch invoices that contain custom items within the date range
        $invoices = Invoice::where('category_id', $category->id)
            ->where(function ($query) use ($from, $to, $isSeasonal) {
                if ($isSeasonal) {
                    $query->whereBetween('created_at', [$from, $to]);
                } else {
                    $query->whereBetween('rental_start_date', [$from, $to])
                        ->orWhereBetween('rental_end_date', [$from, $to])
                        ->orWhere(function ($q) use ($from, $to) {
                            $q->where('rental_start_date', '<=', $to)
                                ->where('rental_end_date', '>=', $from);
                        });
                }
            })
            ->with(['customItems']) // Fetch custom items directly from invoices
            ->get();

        $productBalances = [];

        // Process invoice and additional items
        foreach ($products as $product) {
            $totalQuantity = 0;

            // Calculate totals for original invoice items
            foreach ($product->invoiceItems as $item) {
                $remainingQuantity = $item->quantity - ($item->returned_quantity ?? 0);
                $totalQuantity += $remainingQuantity;
            }

            // Calculate totals for additional items
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

        // Process custom items
        foreach ($invoices as $invoice) {
            foreach ($invoice->customItems as $customItem) {
                $remainingQuantity = $customItem->quantity - ($customItem->returned_quantity ?? 0);

                if ($remainingQuantity > 0) {
                    $productBalances[] = [
                        'product' => $customItem->name, // Custom item name
                        'quantity' => $remainingQuantity,
                    ];
                }
            }
        }

        // Return the view with only the quantities of rented products
        return view('trial-balance.products', compact('productBalances', 'fromDate', 'toDate'));
    }
}
