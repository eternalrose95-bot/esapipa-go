<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    function products()
    {
        return $this->hasMany(Product::class);
    }
}
