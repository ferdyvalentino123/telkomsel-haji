<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Merchandise;


class MerchandiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('merchandises')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('merchandises')->insert([
            [
                'merch_nama' => 'Payung',
                'merch_detail' => 'Payung',
                'merch_stok' => 100,
            ],
            [
                'merch_nama' => 'Bantal Leher',
                'merch_detail' => 'Bantal Leher',
                'merch_stok' => 100,
            ],
            [
                'merch_nama' => 'Tas Serut',
                'merch_detail' => 'Tas Serut',
                'merch_stok' => 100,
            ],
            [
                'merch_nama' => 'Tumblr',
                'merch_detail' => 'Tumblr',
                'merch_stok' => 100,
            ],
            [
                'merch_nama' => 'Kipas',
                'merch_detail' => 'Kipas',
                'merch_stok' => 100,
            ]
        ]);
    }
}
