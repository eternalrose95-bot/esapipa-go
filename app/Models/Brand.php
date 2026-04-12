<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    protected $appends = [
        'logo_url',
    ];

    function products()
    {
        return $this->hasMany(Product::class);
    }

    function categories()
    {
        return $this->hasManyThrough(ProductCategory::class, Product::class);
    }

    public function getDistinctCategoriesAttribute()
    {
        return $this->products()->with('category')->get()->pluck('category')->unique('id');
    }

    function getLogoUrlAttribute()
    {
        return $this->logo_path ?? $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }
}
