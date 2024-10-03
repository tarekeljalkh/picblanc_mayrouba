<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Fields that can be mass assigned
    protected $fillable = ['customer_id', 'vat', 'discount', 'total', 'paid', 'status', 'rental_start_date', 'rental_end_date'];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // If you want to access products through invoice items
    public function products()
    {
        return $this->hasManyThrough(Product::class, InvoiceItem::class, 'invoice_id', 'id', 'id', 'product_id');
    }
}
