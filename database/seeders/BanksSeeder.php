<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['name' => 'Bank Central Asia', 'short_name' => 'BCA', 'sort_code' => '014'],
            ['name' => 'Bank Mandiri', 'short_name' => 'Mandiri', 'sort_code' => '008'],
            ['name' => 'Bank Rakyat Indonesia', 'short_name' => 'BRI', 'sort_code' => '002'],
            ['name' => 'Bank Negara Indonesia', 'short_name' => 'BNI', 'sort_code' => '009'],
            ['name' => 'Bank Syariah Indonesia', 'short_name' => 'BSI', 'sort_code' => '451'],
            ['name' => 'Bank CIMB Niaga', 'short_name' => 'CIMB', 'sort_code' => '022'],
            ['name' => 'Bank Permata', 'short_name' => 'Permata', 'sort_code' => '013'],
            ['name' => 'Bank Danamon', 'short_name' => 'Danamon', 'sort_code' => '011'],
            ['name' => 'Bank Tabungan Negara', 'short_name' => 'BTN', 'sort_code' => '200'],
            ['name' => 'Bank Mega', 'short_name' => 'Mega', 'sort_code' => '426'],
            ['name' => 'Bank BTPN', 'short_name' => 'BTPN', 'sort_code' => '213'],
            ['name' => 'Bank OCBC NISP', 'short_name' => 'OCBC', 'sort_code' => '028'],
            ['name' => 'Bank Panin', 'short_name' => 'Panin', 'sort_code' => '019'],
            ['name' => 'Bank Bukopin', 'short_name' => 'Bukopin', 'sort_code' => '441'],
            ['name' => 'Bank Jago', 'short_name' => 'Jago', 'sort_code' => '542'],
            ['name' => 'Seabank Indonesia', 'short_name' => 'Seabank', 'sort_code' => '535'],
            ['name' => 'Allo Bank', 'short_name' => 'Allo', 'sort_code' => '567'],
            ['name' => 'Bank National Inobu', 'short_name' => 'Nobu', 'sort_code' => '503'],
            ['name' => 'Bank MNC Internasional', 'short_name' => 'MNC', 'sort_code' => '485'],
            ['name' => 'Bank Neo Commerce', 'short_name' => 'Neo', 'sort_code' => '490'],
            ['name' => 'Bank DKI', 'short_name' => 'DKI', 'sort_code' => '111'],
            ['name' => 'Bank Jabar', 'short_name' => 'BJB', 'sort_code' => '110'],
            ['name' => 'Bank Jateng', 'short_name' => 'Jateng', 'sort_code' => '113'],
            ['name' => 'Bank Jatim', 'short_name' => 'Jatim', 'sort_code' => '114'],
            ['name' => 'BPD DIY', 'short_name' => 'DIY', 'sort_code' => '112'],
            ['name' => 'BPD Bali', 'short_name' => 'Bali', 'sort_code' => '129'],
            ['name' => 'Bank Sumut', 'short_name' => 'Sumut', 'sort_code' => '117'],
        ];

        DB::table('banks')->insert($banks);
    }
}
