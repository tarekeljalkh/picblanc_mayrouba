<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    // Fields that can be mass assigned
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'price', 'vat', 'discount', 'total_price', 'damaged', 'damage_charge'];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

        // Calculate Subtotal
        public function getSubtotalAttribute()
        {
            return $this->price * $this->quantity;
        }

        // Calculate VAT Amount
        public function getVatAmountAttribute()
        {
            return ($this->subtotal * $this->vat) / 100;
        }

        // Calculate Discount Amount
        public function getDiscountAmountAttribute()
        {
            return ($this->subtotal * $this->discount) / 100;
        }

        // Calculate Total Price (Subtotal + VAT - Discount)
        public function getTotalPriceAttribute()
        {
            return $this->subtotal + $this->vatAmount - $this->discountAmount;
        }

}
