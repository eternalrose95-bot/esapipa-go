<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            [
                'name' => 'Onda',
                'logo_path' => 'logos/nike.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tuff',
                'logo_path' => 'logos/adidas.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G-Brand',
                'logo_path' => 'logos/apple.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fish',
                'logo_path' => 'logos/samsung.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penguin',
                'logo_path' => 'logos/coca_cola.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Triliun',
                'logo_path' => 'logos/sony.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'YT',
                'logo_path' => 'logos/tesla.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INCO',
                'logo_path' => 'logos/microsoft.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HQ',
                'logo_path' => 'logos/toyota.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AVK',
                'logo_path' => 'logos/toyota.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
                        [
                'name' => 'OEM',
                'logo_path' => 'logos/toyota.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
