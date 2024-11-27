<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',          // ID of the invoice this return belongs to
        'invoice_item_id',     // ID of the specific invoice item
        'returned_quantity',   // Quantity returned
        'days_used',           // Days the returned items were used
        'cost',                // Cost for the returned items
        'return_date',         // Date of the return
    ];

    protected $casts = [
        'return_date' => 'datetime',
    ];

    // Relationships

    /**
     * Get the invoice item associated with this return detail.
     */
    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }

    /**
     * Get the invoice associated with this return detail.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // Accessors

    /**
     * Get the formatted cost for display.
     */
    public function getFormattedCostAttribute()
    {
        return number_format($this->cost, 2);
    }
}
