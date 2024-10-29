<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    // Fields that can be mass assigned
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
        'damaged',
        'damage_charge'
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

    // Calculate Subtotal for this item
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
