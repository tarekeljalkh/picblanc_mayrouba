<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'customer_id',
        'total_vat',
        'total_discount',
        'total_amount',
        'amount_per_day',
        'paid',
        'status',
        'rental_start_date',
        'rental_end_date',
        'days',
        'payment_method', // Added payment method
        'user_id'         // Added user ID
    ];

    // Cast dates to Carbon instances
    protected $casts = [
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Access products through invoice items
    public function products()
    {
        return $this->hasManyThrough(Product::class, InvoiceItem::class, 'invoice_id', 'id', 'id', 'product_id');
    }

    // Calculate subtotal of all items
    public function getSubtotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->total_price);
    }

    // Calculate total VAT based on subtotal and invoice-level VAT percentage
    public function getVatAmountAttribute()
    {
        return ($this->subtotal * $this->total_vat) / 100;
    }

    // Calculate total discount based on subtotal and invoice-level discount percentage
    public function getDiscountAmountAttribute()
    {
        return ($this->subtotal * $this->total_discount) / 100;
    }

    // Calculate final total price (Subtotal + VAT - Discount)
    public function getTotalPriceAttribute()
    {
        return $this->subtotal + $this->vatAmount - $this->discountAmount;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
