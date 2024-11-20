<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
        'rental_start_date',
        'rental_end_date',
        'days'
    ];

    // Automatically cast dates to Carbon instances for easier date handling
    protected $casts = [
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Calculate subtotal for this item
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function histories()
    {
        return $this->hasMany(InvoiceItemHistory::class, 'invoice_item_id');
    }
}
