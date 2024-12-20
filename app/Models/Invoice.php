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


    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    public function additionalItems()
    {
        return $this->hasMany(AdditionalItem::class, 'invoice_id');
    }


    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'invoice_id');
    }


    // Accessors and Calculations

    // Calculate the subtotal (sum of all items' total_price)
    public function getSubtotalAttribute()
    {
        return $this->items->sum('total_price');
    }

    // Calculate the total amount of returns
    public function getReturnedCostAttribute()
    {
        return $this->returnDetails()->sum('cost');
    }

    // Calculate the total amount of added items
    public function getAddedCostAttribute()
    {
        return $this->additionalItems()->sum('total_price');
    }


    // Calculate discount amount
    public function getDiscountAmountAttribute()
    {
        $baseAmount = $this->subtotal + $this->added_cost - $this->returned_cost;
        return ($baseAmount * $this->total_discount) / 100;
    }

    // Calculate the final total
    public function getTotalPriceAttribute()
    {
        $baseAmount = $this->subtotal + $this->added_cost - $this->returned_cost;
        return $baseAmount - $this->discount_amount - $this->deposit;
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
        $itemSubtotal = $this->items->sum(function ($item) {
            return $item->price * $item->quantity * ($item->days ?? 1);
        });

        $additionalItemSubtotal = $this->additionalItems->sum(function ($item) {
            return $item->price * $item->quantity * ($item->days ?? 1);
        });

        $subtotal = $itemSubtotal + $additionalItemSubtotal;

        $discountAmount = ($subtotal * ($this->total_discount ?? 0)) / 100;

        $this->total_amount = $subtotal - $discountAmount;

        $this->save();
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
        return max(0, $this->total_amount - $this->deposit - $this->paid_amount);
    }


    public function calculateTotals()
    {
        $isSeasonal = $this->category->name === 'season';

        // Subtotal: Original invoice total
        $subtotal = $this->items->sum(function ($item) use ($isSeasonal) {
            return $isSeasonal
                ? $item->price * $item->quantity
                : $item->price * $item->quantity * ($item->days ?? 1);
        });

        // Returned Items Cost: Cost for used days of returned items
        $returnedItemsCost = $this->returnDetails->sum(function ($return) {
            $pricePerDay = $return->invoiceItem
                ? $return->invoiceItem->price
                : ($return->additionalItem ? $return->additionalItem->price : 0);

            return $return->days_used * $return->returned_quantity * $pricePerDay;
        });

        // Refund for Unused Days
        $refundForUnusedDays = $this->returnDetails->sum(function ($return) {
            $totalDays = $return->invoiceItem
                ? ($return->invoiceItem->days ?? 1)
                : ($return->additionalItem->days ?? 1);

            $remainingDays = $totalDays - $return->days_used;

            $pricePerDay = $return->invoiceItem
                ? $return->invoiceItem->price
                : ($return->additionalItem ? $return->additionalItem->price : 0);

            return max(0, $remainingDays) * $return->returned_quantity * $pricePerDay;
        });

        // Final Total
        $finalTotal = $returnedItemsCost;

        // Balance Due: Amount still owed
        $totalPaid = $this->deposit + $this->paid_amount;
        $balanceDue = max(0, $finalTotal - $totalPaid);

        return [
            'subtotal' => round($subtotal, 2), // Original invoice total
            'returnedItemsCost' => round($returnedItemsCost, 2), // Cost for used days of returned items
            'refundForUnusedDays' => round($refundForUnusedDays, 2), // Refund for unused days
            'finalTotal' => round($finalTotal, 2), // Final total
            'balanceDue' => round($balanceDue, 2), // Balance due
        ];
    }




    // public function calculateTotals()
    // {
    //     // Subtotal for main items (standard and fixed)
    //     $subtotal = $this->items->sum(function ($item) {
    //         $type = $item->product->type instanceof \App\Enums\ProductType
    //             ? $item->product->type->value
    //             : $item->product->type;

    //         return $type === 'fixed'
    //             ? $item->price * $item->quantity // Fixed: price * quantity
    //             : $item->price * $item->quantity * $item->days; // Standard: price * quantity * days
    //     });

    //     // Additional costs for additional items
    //     $additionalCost = $this->additionalItems->sum(function ($item) {
    //         $type = $item->product->type instanceof \App\Enums\ProductType
    //             ? $item->product->type->value
    //             : $item->product->type;

    //         return $type === 'fixed'
    //             ? $item->price * $item->quantity // Fixed: price * quantity
    //             : $item->price * $item->quantity * $item->days; // Standard: price * quantity * days
    //     });

    //     // Costs for returned items
    //     $returnedCost = $this->returnDetails->sum(function ($return) {
    //         return $return->returned_quantity * $return->invoiceItem->price * $return->days_used;
    //     });

    //     // Total before discount
    //     $totalBeforeDiscount = $subtotal + $additionalCost - $returnedCost;

    //     // Discount amount
    //     $discountAmount = ($totalBeforeDiscount * ($this->total_discount ?? 0) / 100);

    //     // Final total after discount
    //     $total = $totalBeforeDiscount - $discountAmount;

    //     return [
    //         'subtotal' => round($subtotal, 2),
    //         'additionalCost' => round($additionalCost, 2),
    //         'returnedCost' => round($returnedCost, 2),
    //         'discountAmount' => round($discountAmount, 2),
    //         'total' => round($total, 2),
    //     ];
    // }


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
        // Dynamically calculate the total including additional items
        $totalAmount = $this->total_amount + $this->additionalItems->sum('total_price');
        $paidAmount = $this->paid_amount;

        // Determine the payment status
        if ($paidAmount >= $totalAmount) {
            return 'fully_paid';
        }

        if ($paidAmount > 0) {
            return 'partially_paid';
        }

        return 'unpaid';
    }

    public function getTotalWithAdditionalAttribute()
    {
        return $this->total_amount + $this->additionalItems->sum('total_price');
    }
}
