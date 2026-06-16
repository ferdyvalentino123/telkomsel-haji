<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchandiseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('merchandises')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('merchandises')->insert([
  0 => [
    'deleted_at' => NULL,
    'id' => 1,
    'merch_nama' => 'Payung Fantasi',
    'merch_detail' => 'Payung Fantasi',
    'merch_stok' => 100,
    'created_at' => NULL,
    'updated_at' => '2026-06-16T17:09:20.000000Z',
    'stok_terakhir' => 0,
    'merch_terambil' => 0,
    'merch_terambil_history' => NULL,
  ],
  1 => [
    'deleted_at' => NULL,
    'id' => 2,
    'merch_nama' => 'Bantal Leher',
    'merch_detail' => 'Bantal Leher',
    'merch_stok' => 100,
    'created_at' => NULL,
    'updated_at' => NULL,
    'stok_terakhir' => 0,
    'merch_terambil' => 0,
    'merch_terambil_history' => NULL,
  ],
  2 => [
    'deleted_at' => NULL,
    'id' => 3,
    'merch_nama' => 'Tas Serut',
    'merch_detail' => 'Tas Serut',
    'merch_stok' => 100,
    'created_at' => NULL,
    'updated_at' => NULL,
    'stok_terakhir' => 0,
    'merch_terambil' => 0,
    'merch_terambil_history' => NULL,
  ],
  3 => [
    'deleted_at' => NULL,
    'id' => 4,
    'merch_nama' => 'Tumblr',
    'merch_detail' => 'Tumblr',
    'merch_stok' => 100,
    'created_at' => NULL,
    'updated_at' => NULL,
    'stok_terakhir' => 0,
    'merch_terambil' => 0,
    'merch_terambil_history' => NULL,
  ],
  4 => [
    'deleted_at' => NULL,
    'id' => 5,
    'merch_nama' => 'Kipas',
    'merch_detail' => 'Kipas',
    'merch_stok' => 100,
    'created_at' => NULL,
    'updated_at' => NULL,
    'stok_terakhir' => 0,
    'merch_terambil' => 0,
    'merch_terambil_history' => NULL,
  ],
]);
    }
}
