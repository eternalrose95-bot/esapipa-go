<?php

namespace App\Models;

use App\Models\Product;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_purchase',
            'purchase_id', //foreign key di pivot ke purchase
            'product_id' //foreign key ke product
        )->withPivot(['quantity', 'unit_price', 'discount_percentage', 'notes']);
    }

    public function getTotalValueAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->quantity * $product->purchase_price;
        });
    }

    public function getTotalAmountAttribute()
    {
        return $this->grand_total;
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
            return $this->grand_total - $totalPaid;
        }

        // Fallback for when payments are not loaded (should be avoided)
        return $this->grand_total - $this->total_paid;
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
        return $this->payments()->sum('purchase_purchase_payment.amount');
    }



    function payments()
    {
        return $this->belongsToMany(PurchasePayment::class, 'purchase_purchase_payment')->withTimestamps()->withPivot(['amount']);
    }
}
