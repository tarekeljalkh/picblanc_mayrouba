<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItemHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
