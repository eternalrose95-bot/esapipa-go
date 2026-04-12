<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saleIds = [];
        // $saleDates = [
        //     '2024-09-05',
        //     '2024-09-15',
        //     '2024-09-22',
        //     '2024-10-05',
        //     '2024-11-05',
        //     '2024-10-15',
        //     '2024-11-15',
        //     '2024-10-22',
        //     '2024-10-28',
        //     '2024-11-28',
        //     '2024-10-30',
        //     '2024-11-30',
        // ];

        $saleIds = [];

        // foreach ($saleDates as $key => $date) {
        //     $products = Product::all();

        //     $sale = new Sale();
        //     $sale->client_id = rand(1, count(Client::all()));
        //     $sale->sale_date = $date;
        //     $sale->save();

        //     $saleIds[] = $sale->id;


        //     foreach ($products as $key => $product) {

        //         if ($product->inventory_balance < 1) {
        //             continue;
        //         }

        //         $sale->products()->attach($product->id, [
        //             'quantity' => rand(1, $product->inventory_balance),
        //             'unit_price' => rand($product->purchase_price * 0.8, $product->sale_price * 1.2),
        //         ]);

        //         if ($sale->total_quantity < 1) {
        //             $sale->products()->detach();
        //             $sale->delete();
        //         }
        //     }
        // }
    }
}
