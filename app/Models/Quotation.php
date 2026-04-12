<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = ['quotation_date', 'client_name', 'subtotal', 'tax_percentage', 'tax_amount', 'grand_total'];

    protected $appends = [
        'total_amount'
    ];
    // Removed client relationship since client_name is now manual input
    function products()
    {
        return $this->belongsToMany(Product::class, 'product_quotation')->withPivot(['quantity', 'unit_price', 'discount_percentage', 'notes']);
    }

    public function getTotalAmountAttribute()
    {
        $amount = $this->grand_total ?? $this->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->unit_price;
        });

        return round($amount);
    }
}
