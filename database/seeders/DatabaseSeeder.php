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
        $audio = Category::firstOrCreate(['name' => 'Audio'], ['prefix' => 'AU']);
        $video = Category::firstOrCreate(['name' => 'Video'], ['prefix' => 'VI']);

        // buat beberapa jenis barang
        Item::firstOrCreate(
            ['name' => 'Mic Shure SM58'],
            [
                'category_id' => $audio->id,
            ],
        );
        Item::firstOrCreate(
            ['name' => 'Camera Sony XDCAM'],
            [
                'category_id' => $video->id,
            ],
        );

        // buat partner contoh
        Partner::firstOrCreate(['name' => 'Program A'], ['unit' => 'Program']);
    }
}
