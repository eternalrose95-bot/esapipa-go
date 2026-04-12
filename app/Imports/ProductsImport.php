<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\Size;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Find or create related models
        $brand = Brand::firstOrCreate(['name' => $row['brand']]);
        $category = ProductCategory::firstOrCreate(['name' => $row['category']]);
        $unit = Unit::firstOrCreate(['name' => $row['unit']]);
        $size = Size::firstOrCreate(['name' => $row['size']]);

        return new Product([
            'brand_id' => $brand->id,
            'product_category_id' => $category->id,
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
            'unit_id' => $unit->id,
            'size_id' => $size->id,
            'purchase_price' => $row['purchase_price'],
            'sale_price' => $row['sale_price'],
            'sku' => $row['sku'],
            'image' => $row['image'] ?? null,
        ]);
    }
}