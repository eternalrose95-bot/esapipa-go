<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_date',
        'client_id',
        'subtotal',
        'tax_percentage',
        'tax_amount',
        'grand_total',
    ];

    protected $appends = [
        'total_amount'
    ];
    function client()
    {
        return $this->belongsTo(Client::class);
    }
    function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_product')->withPivot(['quantity', 'unit_price', 'discount_percentage']);
    }

    public function getTotalAmountAttribute()
    {
        return round($this->grand_total ?? 0);
    }
}
