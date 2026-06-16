<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportSeeders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:seeders';

    protected $description = 'Export current database Produks and Merchandises to Seeder files';

    public function handle()
    {
        $this->info('Exporting Produks...');
        $produks = \App\Models\Produk::withTrashed()->get()->map(function ($item) {
            $arr = $item->toArray();
            unset($arr['produk_terjual_history']); // Assuming we don't seed this
            unset($arr['produk_harga_akhir']); // Virtual/generated column - cannot be inserted
            return $arr;
        })->toArray();
        $this->generateSeeder('ProdukSeeder', 'produks', $produks);

        $this->info('Exporting Merchandises...');
        $merchandises = \App\Models\Merchandise::withTrashed()->get()->toArray();
        $this->generateSeeder('MerchandiseSeeder', 'merchandises', $merchandises);

        $this->info('Seeders updated successfully! You can now commit and push to your repository.');
    }

    private function generateSeeder($className, $tableName, $data)
    {
        $arrayString = var_export($data, true);
        
        // Convert array syntax from array() to []
        $arrayString = preg_replace('/array \(/', '[', $arrayString);
        $arrayString = preg_replace('/\),/m', '],', $arrayString);
        $arrayString = preg_replace('/\)$/m', ']', $arrayString);
        $arrayString = preg_replace('/=> \n\s+\[/', '=> [', $arrayString);
        
        // Convert ISO 8601 datetime to MySQL datetime (e.g. 2026-06-16T17:09:20.000000Z -> 2026-06-16 17:09:20)
        $arrayString = preg_replace('/(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2})\.\d+Z/', '$1 $2', $arrayString);

        $stub = "<?php\n\nnamespace Database\Seeders;\n\nuse Illuminate\Database\Seeder;\nuse Illuminate\Support\Facades\DB;\n\nclass {$className} extends Seeder\n{\n    public function run(): void\n    {\n        DB::statement('SET FOREIGN_KEY_CHECKS=0');\n        DB::table('{$tableName}')->truncate();\n        DB::statement('SET FOREIGN_KEY_CHECKS=1');\n\n        DB::table('{$tableName}')->insert({$arrayString});\n    }\n}\n";

        file_put_contents(database_path("seeders/{$className}.php"), $stub);
    }
}
