<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseIds = [];
        // $purchaseDates = [
        //     '2024-09-05',
        //     '2024-09-15',
        //     '2024-09-22',
        //     '2024-09-22',
        //     '2024-09-28',
        //     '2024-09-30',
        //     '2024-10-05',
        //     '2024-11-05',
        //     '2024-10-15',
        //     '2024-11-15',
        //     '2024-10-22',
        //     '2024-11-22',
        //     '2024-10-22',
        //     '2024-11-22',
        //     '2024-10-28',
        //     '2024-11-28',
        //     '2024-10-30',
        //     '2024-10-31',
        //     '2024-11-30',
        // ];


        // foreach ($purchaseDates as $key => $date) {
        //     $purchaseId = DB::table('purchases')->insertGetId([
        //         'supplier_id' => rand(1, count(Supplier::all())),
        //         'purchase_date' => $date,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        //     $purchaseIds[] = $purchaseId;
        //     // array_push($purchaseIds, $purchaseId);
        // }


        $products = Product::all();

        // foreach ($purchaseIds as $key => $id) {
        //     foreach ($products as $key => $product) {
        //         DB::table('product_purchase')->insert([
        //             'product_id' => $product->id,
        //             'purchase_id' => $id,
        //             'quantity' => rand(20, 1000),
        //             'unit_price' => rand($product->purchase_price, $product->sale_price),
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }
    }
}
