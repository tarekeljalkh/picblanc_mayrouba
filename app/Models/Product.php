<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'type',
    ];

    protected $casts = [
        'type' => ProductType::class,
    ];

    use HasFactory;

    // Relationship to get invoice items for the product
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'product_id');
    }

        // Relationship to get returned items for the product
        public function returnedItems()
        {
            return $this->hasMany(ReturnDetail::class, 'product_id');
        }

    // Calculate the total rented quantity (active invoices only)
    public function rentedQuantity()
    {
        return $this->invoiceItems()
            ->whereHas('invoice', function ($query) {
                $query->where('status', 'active'); // Only consider active rentals
            })
            ->sum('quantity');
    }

    // Calculate the total returned quantity
    public function returnedQuantity()
    {
        return $this->returnedItems()->sum('returned_quantity');
    }


    // Get the detailed rentals including customer and dates
    public function rentals()
    {
        return $this->invoiceItems()->whereHas('invoice', function ($query) {
            $query->where('status', 'active');
        });
    }

        // Define the relationship with Category
        public function category()
        {
            return $this->belongsTo(Category::class);
        }

}
