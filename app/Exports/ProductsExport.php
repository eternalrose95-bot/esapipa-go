<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with(['brand', 'category', 'unit', 'size'])->get()->map(function ($product) {
            return [
                'Brand' => $product->brand->name ?? '',
                'Category' => $product->category->name ?? '',
                'Name' => $product->name,
                'Description' => $product->description,
                'Unit' => $product->unit->name ?? '',
                'Size' => $product->size->name ?? '',
                'Purchase Price' => $product->purchase_price,
                'Sale Price' => $product->sale_price,
                'SKU' => $product->sku,
                'Image' => $product->image,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Brand',
            'Category',
            'Name',
            'Description',
            'Unit',
            'Size',
            'Purchase Price',
            'Sale Price',
            'SKU',
            'Image',
        ];
    }
}