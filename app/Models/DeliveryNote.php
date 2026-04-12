<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    protected $fillable = [
        'driver_name',
        'vehicle_number',
        'receiver_name',
        'sale_id',
    ];

    function products()
    {
        return $this->belongsToMany(Product::class, 'delivery_note_product');
    }

    function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
