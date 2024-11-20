<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

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
        'payment_method',
        'note',
        'user_id'
    ];

    protected $casts = [
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, InvoiceItem::class, 'invoice_id', 'id', 'id', 'product_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->total_price);
    }

    public function getVatAmountAttribute()
    {
        return ($this->subtotal * $this->total_vat) / 100;
    }

    public function getDiscountAmountAttribute()
    {
        return ($this->subtotal * $this->total_discount) / 100;
    }

    public function getTotalPriceAttribute()
    {
        return $this->subtotal + $this->vatAmount - $this->discountAmount;
    }

    public function getBalanceAttribute()
    {
        return $this->paid ? 0 : $this->totalPrice;
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->totalPrice, 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
