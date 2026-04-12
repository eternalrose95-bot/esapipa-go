<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];
    protected $appends = [
        'total_amount'
    ];
    function client()
    {
        return $this->belongsTo(Client::class);
    }
    function products()
    {
        return $this->belongsToMany(Product::class, 'product_sale')->withPivot(['quantity', 'unit_price', 'discount_percentage', 'notes']);
    }

    public function getTotalValueAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->quantity * $product->purchase_price;
        });
    }
    public function getTotalAmountAttribute()
    {
        // Return the stored grand_total (which includes tax), or fallback to calculated subtotal
        return $this->grand_total ?? $this->products->sum(function ($product) {
            $unitPrice = $product->pivot->unit_price;
            $discount = $product->pivot->discount_percentage ?? 0;
            $finalPrice = $unitPrice - ($unitPrice * $discount / 100);
            return $product->pivot->quantity * $finalPrice;
        });
    }
    public function getTotalQuantityAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->quantity;
        });
    }

    function getTotalBalanceAttribute()
    {
        // Optimized: use loaded payments if available, otherwise calculate
        if ($this->relationLoaded('payments')) {
            $totalPaid = $this->payments->sum(function ($payment) {
                return $payment->pivot->amount;
            });
            return $this->total_amount - $totalPaid;
        }

        // Fallback for when payments are not loaded (should be avoided)
        return $this->total_amount - $this->total_paid;
    }

    function getIsPaidAttribute()
    {
        return $this->total_balance <= 0;
    }

    function getTotalPaidAttribute()
    {
        // Optimized: use loaded payments if available
        if ($this->relationLoaded('payments')) {
            return $this->payments->sum(function ($payment) {
                return $payment->pivot->amount;
            });
        }

        // Fallback: calculate from database (more expensive)
        return $this->payments()->sum('sale_sale_payment.amount');
    }


    function payments()
    {
        return $this->belongsToMany(SalesPayment::class, 'sale_sale_payment')->withPivot(['amount']);
    }
}
