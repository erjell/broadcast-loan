<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;
use App\Models\Partner;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application’s database.
     */
    public function run(): void
    {
        // buat kategori default
        $audio = Category::firstOrCreate(['name' => 'Audio']);
        $video = Category::firstOrCreate(['name' => 'Video']);

        // buat beberapa barang
        Item::firstOrCreate(
            ['barcode' => 'MIC-0001'],
            [
                'name'        => 'Mic Shure SM58',
                'category_id' => $audio->id,
                'stock'       => 10,
            ],
        );
        Item::firstOrCreate(
            ['barcode' => 'CAM-0101'],
            [
                'name'        => 'Camera Sony XDCAM',
                'category_id' => $video->id,
                'stock'       => 3,
            ],
        );

        // buat partner contoh
        Partner::firstOrCreate(['name' => 'Program A'], ['unit' => 'Program']);
    }
}
