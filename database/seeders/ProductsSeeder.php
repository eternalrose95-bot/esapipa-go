<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = Size::pluck('id')->toArray();
        
        DB::table('products')->insert([
            [
                'brand_id' => 11,
                'product_category_id' => 1, 
                'name' => 'Pipa HDPE PN 10',
                'description' => 'High-performance laptop with an Intel i7 processor and 16GB RAM.',
                'unit_id' => 2, 
                'size_id' => $sizes[array_rand($sizes)],
                'purchase_price' => 150000.00,
                'sale_price' => 170000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_id' => 11,
                'product_category_id' => 1, 
                'name' => 'Pipa HDPE PN 12.5',
                'description' => 'Latest smartphone with advanced camera features and 256GB storage.',
                'unit_id' => 2, 
                'size_id' => $sizes[array_rand($sizes)],
                'purchase_price' => 80000.00,
                'sale_price' => 95000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_id' => 1,
                'product_category_id' => 2, 
                'name' => 'Meteran Air 1/2"',
                'description' => 'Premium tablet with M2 chip and 128GB storage.',
                'unit_id' => 1,   
                'size_id' => $sizes[array_rand($sizes)],
                'purchase_price' => 90000.00,
                'sale_price' => 105000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_id' => 1,
                'product_category_id' => 2, 
                'name' => 'Seal Tape 10 Meter',
                'description' => 'Bluetooth 5.0 wireless earbuds with noise cancellation.',
                'unit_id' => 1, // Piece
                'size_id' => $sizes[array_rand($sizes)],
                'purchase_price' => 3000.00,
                'sale_price' => 4500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_id' => 11, //
                'product_category_id' => 3, // PC Components
                'name' => 'Socket Stub End HDPE PN 16',
                'description' => 'High-end graphics card for gaming and creative work.',
                'unit_id' => 1, // Piece
                'size_id' => $sizes[array_rand($sizes)],
                'purchase_price' => 200000.00,
                'sale_price' => 250000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_id' => 11, //
                'product_category_id' => 3, // Networking Equipment
                'name' => 'Male Socket HDPE (Sock Drat Luar) PN 16',
                'description' => 'Dual-band Wi-Fi 6 router with high-speed connectivity.',
                'unit_id' => 1, // Piece
                'size_id' => $sizes[array_rand($sizes)],
                'purchase_price' => 15000.00,
                'sale_price' => 18000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_id' => 11, //
                'product_category_id' => 7, // Gaming Consoles
                'name' => 'Ball Valve HDPE (Socket Fusion) PN 10',
                'description' => 'Next-generation gaming console with 4K resolution and dual sense controller.',
                'unit_id' => 1, // Piece
                'size_id' => $sizes[array_rand($sizes)],
                'purchase_price' => 60000.00,
                'sale_price' => 70000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
