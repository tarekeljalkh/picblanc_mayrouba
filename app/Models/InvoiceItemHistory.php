<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItemHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_item_id',
        'invoice_id',
        'product_id',
        'action',
        'previous_quantity',
        'new_quantity',
        'previous_price',
        'new_price',
        'change_reason',
    ];

    // Casting for numeric fields to ensure consistent data types
    protected $casts = [
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'previous_price' => 'decimal:2',
        'new_price' => 'decimal:2',
    ];

    /**
     * Relationship with InvoiceItem.
     */
    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    /**
     * Relationship with Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relationship with Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
