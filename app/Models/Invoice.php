<?php

namespace App\Models;

use App\Enums\ProductType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',
        'category_id',
        'total_discount',
        'total_amount',
        'deposit',
        'paid_amount',
        'payment_method',
        'status',
        'rental_start_date',
        'rental_end_date',
        'days',
        'note',
    ];

    protected $casts = [
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function customItems()
    {
        return $this->hasMany(CustomItem::class);
    }



    public function additionalItems()
    {
        return $this->hasMany(AdditionalItem::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'invoice_id');
    }


    // Accessors and Calculations

    // Calculate the subtotal (sum of all items' total_price)
    public function getSubtotalAttribute()
    {
        return $this->invoiceItems->sum('total_price');
    }

    // Calculate the total amount of returns
    public function getReturnedCostAttribute()
    {
        return $this->returnDetails->sum('cost');
    }

    // Calculate the total amount of added items
    public function getAddedCostAttribute()
    {
        return $this->additionalItems->sum('total_price');
    }


    // Calculate discount amount
    public function getDiscountAmountAttribute()
    {
        return ($this->subtotal * $this->total_discount) / 100;
    }

    // Calculate the final total
    // Final total dynamically calculated, including adjustments
    public function getTotalPriceAttribute()
    {
        $subtotal = $this->subtotal; // Base total of original items
        $additionalItemsTotal = $this->added_cost; // Total of additional items
        $returnedCost = $this->returned_cost; // Cost of returned items
        $discountAmount = $this->discount_amount; // Discount based on subtotal

        // Final Total Calculation
        return ($subtotal + $additionalItemsTotal - $returnedCost) - $discountAmount;
    }



    // Get total returned quantity
    public function getTotalReturnedQuantityAttribute()
    {
        return $this->returnDetails->sum('returned_quantity');
    }

    // Get total added quantities
    public function getTotalAddedQuantityAttribute()
    {
        return $this->additionalItems->sum('quantity');
    }

    // Update and save invoice totals
    public function recalculateTotals()
    {
        // Subtotal for original items
        $itemSubtotal = $this->invoiceItems->sum(function ($item) {
            return $item->price * $item->quantity * ($item->days ?? 1);
        });

        // Subtotal for additional items
        $additionalItemSubtotal = $this->additionalItems->sum(function ($item) {
            return $item->price * $item->quantity * ($item->days ?? 1);
        });

        // Total adjustments
        $subtotal = $itemSubtotal + $additionalItemSubtotal;

        $discountAmount = ($subtotal * ($this->total_discount ?? 0)) / 100;

        // Total amount is calculated dynamically but not saved
        return [
            'subtotal' => $subtotal,
            'discountAmount' => $discountAmount,
            'finalTotal' => $subtotal - $discountAmount,
        ];
    }

    // public function recalculateTotals()
    // {
    //     $totals = $this->calculateTotals();

    //     $this->total_amount = $totals['total'];
    //     $this->save();
    // }

    // Query Scopes for Filtering
    public function scopePaid($query)
    {
        return $query->where('paid', 1);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('paid', 0);
    }

    public function scopeOverdue($query)
    {
        return $query->where('rental_end_date', '<', now())->where('paid', 0);
    }

    public function getBalanceDueAttribute()
    {
        $finalTotal = $this->total_price; // Dynamically calculated final total

        // Fetch total payments made for this invoice
        $totalPaid = $this->payments->sum('amount');

        // Calculate the balance due correctly
        return max(0, $finalTotal - $totalPaid);
    }


    public function calculateTotals()
    {
        $isSeasonal = $this->category->name === 'season';

        // Subtotal: regular + custom items
        $subtotalForDiscount = $this->invoiceItems->sum(function ($item) use ($isSeasonal) {
            return $isSeasonal
                ? $item->price * $item->quantity
                : $item->price * $item->quantity * ($item->days ?? 1);
        }) + $this->customItems->sum(function ($customItem) use ($isSeasonal) {
            return $isSeasonal
                ? $customItem->price * $customItem->quantity
                : $customItem->price * $customItem->quantity * ($this->days ?? 1);
        });

        // Additional Items Cost
        $additionalItemsCost = $this->additionalItems->sum(function ($item) {
            return $item->price * $item->quantity * ($item->days ?? 1);
        });

        $totalSubtotal = $subtotalForDiscount + $additionalItemsCost;

        // Discount
        $discountPercentage = $this->total_discount ?? 0;
        $discountAmount = ($subtotalForDiscount * $discountPercentage) / 100;

        // Returned Items Cost
        $returnedItemsCost = $this->returnDetails->sum(function ($return) {
            $price = $return->invoiceItem?->price ?? $return->additionalItem?->price ?? $return->customItem?->price ?? 0;
            return $return->days_used * $return->returned_quantity * $price;
        });

        // Refund for Unused Days
        $refundForUnusedDays = $this->returnDetails->sum(function ($return) {
            $totalDays =
                $return->invoiceItem?->days ??
                $return->additionalItem?->days ??
                $return->customItem?->invoice->days ??
                1;

            $price = $return->invoiceItem?->price ?? $return->additionalItem?->price ?? $return->customItem?->price ?? 0;

            $unused = max(0, $totalDays - $return->days_used);

            return $unused * $return->returned_quantity * $price;
        });

        $finalTotal = $totalSubtotal - $discountAmount;

        $totalPaid = $this->payments->sum('amount');

        return [
            'subtotal' => round($totalSubtotal, 2),
            'subtotalForDiscount' => round($subtotalForDiscount, 2),
            'additionalItemsCost' => round($additionalItemsCost, 2),
            'discountAmount' => round($discountAmount, 2),
            'returnedItemsCost' => round($returnedItemsCost, 2),
            'refundForUnusedDays' => round($refundForUnusedDays, 2),
            'finalTotal' => round($finalTotal, 2),
            'finalTotalCustom' => round($subtotalForDiscount + $additionalItemsCost - $refundForUnusedDays - $discountAmount - $totalPaid, 2),
            'balanceDue' => round(max(0, $finalTotal - $totalPaid - $refundForUnusedDays), 2),
        ];
    }

    // public function calculateTotals()
    // {
    //     $isSeasonal = $this->category->name === 'season';

    //     // ✅ Subtotal: Includes regular items and custom items
    //     $subtotalForDiscount = $this->invoiceItems->sum(function ($item) use ($isSeasonal) {
    //         return $isSeasonal
    //             ? $item->price * $item->quantity
    //             : $item->price * $item->quantity * ($item->days ?? 1);
    //     }) + $this->customItems->sum(function ($customItem) use ($isSeasonal) {
    //         return $isSeasonal
    //             ? $customItem->price * $customItem->quantity
    //             : $customItem->price * $customItem->quantity * ($this->days ?? 1); // ✅ Multiply by days if daily
    //     });

    //     // ✅ Additional Items Cost
    //     $additionalItemsCost = $this->additionalItems->sum(function ($additionalItem) {
    //         return $additionalItem->price * $additionalItem->quantity * ($additionalItem->days ?? 1);
    //     });

    //     // ✅ Total Subtotal (regular items + custom items + additional items)
    //     $totalSubtotal = $subtotalForDiscount + $additionalItemsCost;

    //     // ✅ Discount Calculation
    //     $discountPercentage = $this->total_discount ?? 0;
    //     $discountAmount = ($subtotalForDiscount * $discountPercentage) / 100;

    //     // ✅ Returned Items Cost: Handles regular, additional, and custom items
    //     $returnedItemsCost = $this->returnDetails->sum(function ($return) {
    //         $pricePerDay = $return->invoiceItem
    //             ? $return->invoiceItem->price
    //             : ($return->additionalItem
    //                 ? $return->additionalItem->price
    //                 : ($return->customItem ? $return->customItem->price : 0)); // ✅ Handle custom items

    //         return $return->days_used * $return->returned_quantity * $pricePerDay;
    //     });

    //     // ✅ Refund for Unused Days (Handles custom items as well)
    //     $refundForUnusedDays = $this->returnDetails->sum(function ($return) {
    //         $totalDays = $return->invoiceItem
    //             ? ($return->invoiceItem->days ?? 1)
    //             : ($return->additionalItem
    //                 ? $return->additionalItem->days
    //                 : ($return->customItem ? $return->customItem->invoice->days : 1)); // ✅ Handle custom items

    //         $unusedDays = $totalDays - $return->days_used;

    //         $pricePerDay = $return->invoiceItem
    //             ? $return->invoiceItem->price
    //             : ($return->additionalItem
    //                 ? $return->additionalItem->price
    //                 : ($return->customItem ? $return->customItem->price : 0)); // ✅ Handle custom items

    //         return max(0, $unusedDays) * $return->returned_quantity * $pricePerDay;
    //     });

    //     // ✅ Total after applying discount
    //     $totalAfterDiscount = $totalSubtotal - $discountAmount;

    //     // ✅ Final Total Calculation
    //     $finalTotal = $totalAfterDiscount;

    //     // ✅ Fetch all payments for this invoice
    //     $totalPayments = $this->payments->sum('amount');  // Assuming payments are related via the `invoice_id`

    //     // ✅ Balance Due Calculation (using payments instead of deposit + paid_amount)
    //     $balanceDue = max(0, $finalTotal - $totalPayments);

    //     // ✅ Custom Final Total (For display purposes)
    //     $finalTotalCustom = $subtotalForDiscount + $additionalItemsCost - $refundForUnusedDays - $discountAmount - $totalPayments;

    //     // ✅ Returning all calculated totals
    //     return [
    //         'subtotal' => round($totalSubtotal, 2),               // Total of all items including additional items
    //         'subtotalForDiscount' => round($subtotalForDiscount, 2), // Subtotal eligible for discount
    //         'additionalItemsCost' => round($additionalItemsCost, 2), // Cost of additional items
    //         'discountAmount' => round($discountAmount, 2),        // Discount amount
    //         'returnedItemsCost' => round($returnedItemsCost, 2),  // Cost for returned items (includes custom items)
    //         'refundForUnusedDays' => round($refundForUnusedDays, 2), // Refund for unused rental days
    //         'finalTotal' => round($finalTotal, 2),                // Final total after all adjustments
    //         'finalTotalCustom' => round($finalTotalCustom, 2),    // Custom final total for display
    //         'balanceDue' => round(max(0, $finalTotal - $totalPayments - $refundForUnusedDays), 2), // Remaining balance due
    //     ];
    // }


    public function getReturnedAttribute()
    {
        // Check if all regular items are returned
        $allItemsReturned = $this->invoiceItems->every(function ($item) {
            return $item->quantity <= $item->returned_quantity;
        });

        // Check if all additional items are returned
        $allAdditionalItemsReturned = $this->additionalItems->every(function ($item) {
            return $item->quantity <= $item->returned_quantity;
        });

        // Check if all custom items are returned
        $allCustomItemsReturned = $this->customItems->every(function ($item) {
            return $item->quantity <= $item->returned_quantity;
        });

        // Return true if all items are returned, otherwise false
        return $allItemsReturned && $allAdditionalItemsReturned && $allCustomItemsReturned;
    }

    public function checkAndUpdateStatus()
    {
        $totalAmount = $this->total_amount + $this->additionalItems->sum('total_price');
        $paidAmount = $this->paid_amount;

        if ($totalAmount <= $paidAmount) {
            $this->status = 'active';
        } elseif ($this->rental_end_date && $this->rental_end_date < now() && $paidAmount < $totalAmount) {
            $this->status = 'overdue';
        } else {
            $this->status = 'draft';
        }

        $this->save();
    }


    public function getPaymentStatusAttribute()
    {
        $totals = $this->calculateTotals(); // Ensure accurate totals calculation
        $balanceDue = $totals['balanceDue'];

        // Fetch total paid amount from the invoice_payments table
        $totalPaid = $this->payments->sum('amount');

        // ✅ Set a tolerance threshold (e.g., $1.00)
        $tolerance = 1.00;

        // ✅ Determine the payment status based on payments and balance due
        if ($totalPaid <= 0 && $balanceDue > $tolerance) {
            return 'unpaid';
        }

        if ($balanceDue <= $tolerance) {
            return 'fully_paid';
        }

        return 'partially_paid';
    }

    // public function getPaymentStatusAttribute()
    // {
    //     $totals = $this->calculateTotals(); // Ensure accurate totals calculation
    //     $balanceDue = $totals['balanceDue'];

    //     // Fetch total paid amount from the invoice_payments table
    //     $totalPaid = $this->payments()->sum('amount');

    //     // Determine the payment status based on actual payments
    //     if ($totalPaid <= 0 && $balanceDue > 0) {
    //         return 'unpaid';
    //     }

    //     if ($totalPaid > 0 && $balanceDue > 0) {
    //         return 'partially_paid';
    //     }

    //     if ($balanceDue <= 0 && $totalPaid >= $totals['finalTotal']) {
    //         return 'fully_paid';
    //     }

    //     return 'unknown'; // Fallback case
    // }



    public function getTotalWithAdditionalAttribute()
    {
        return $this->total_amount + $this->added_cost - $this->returned_cost;
    }
}
