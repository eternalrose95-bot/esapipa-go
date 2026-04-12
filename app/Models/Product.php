<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'product_category_id',
        'name',
        'description',
        'unit_id',
        'size_id',
        'purchase_price',
        'sale_price',
        'sku',
        'image',
    ];

    function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    function size()
    {
        return $this->belongsTo(Size::class);
    }
    function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    function sales()
    {
        return $this->belongsToMany(Sale::class, 'product_sale')->withPivot(['quantity', 'unit_price']);
    }
    function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'product_purchase')
        ->withPivot('quantity');
    }
    function quotations()
    {
        return $this->belongsToMany(Quotation::class, 'product_quotation')->withPivot(['quantity', 'unit_price', 'discount_percentage']);
    }
    function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')->withPivot(['quantity', 'notes']);
    }
    function invoices()
    {
        return $this->belongsToMany(Invoice::class,  'invoice_product')->withPivot(['quantity', 'unit_price']);
    }
    function deliveryNotes()
    {
        return $this->belongsToMany(DeliveryNote::class, 'delivery_note_product');
    }
    function creditNotes()
    {
        return $this->belongsToMany(CreditNote::class, 'credit_note_product');
    }

    function getTotalPurchaseCountAttribute(){
        $amount = 0;
        foreach ($this->purchases as $purchase)
        {
            $amount += ($purchase->pivot->quantity);
        }

        return $amount;
    }
    function getTotalSaleCountAttribute(){

        if (!$this->relationLoaded('sales')) {
            return 0;
        }

        return $this->sales->sum(function ($sale) {
        return $sale->pivot->quantity;
        });
    }

    function getInventoryBalanceAttribute()
    {
        return $this->total_purchase_count - $this->total_sale_count;
    }



}
