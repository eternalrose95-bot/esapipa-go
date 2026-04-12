<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'CV Untung Berkah',
                'email' => 'untung@berkah.co.id',
                'address' => 'Jakarta, Indonesia',
                'phone_number' => '+62  812 345 678',
                'tax_id' => 'P0123456789Q',
                'bank_id' => 1, // Adjust this ID based on your actual `banks` table
                'account_number' => '1234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Berkah Jaya',
                'email' => 'berkah@jaya.com',
                'address' => 'Bandung, Indonesia',
                'phone_number' => '+62 987 654 321',
                'tax_id' => 'PVT87654321',
                'bank_id' => 2, // Adjust this ID based on your actual `banks` table
                'account_number' => '0987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CV Sinar Gemilang',
                'email' => 'sinar@gemilang.com',
                'address' => 'Sumatera, Indonesia',
                'phone_number' => '+62 879 657 435',
                'tax_id' => 'PVT12398765',
                'bank_id' => 3, // Adjust this ID based on your actual `banks` table
                'account_number' => '1122334455',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tafio Group',
                'email' => 'tafio@group.com',
                'address' => 'Malang, Indonesia',
                'phone_number' => '+62 768 546 324',
                'tax_id' => 'PVT34567890',
                'bank_id' => 4, // Adjust this ID based on your actual `banks` table
                'account_number' => '2233445566',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laken Surabaya',
                'email' => 'surabaya@laken.com',
                'address' => 'Surabaya, Indonesia',
                'phone_number' => '+62 21 328 8000',
                'tax_id' => 'PVT56789012',
                'bank_id' => 5, // Adjust this ID based on your actual `banks` table
                'account_number' => '3344556677',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
