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
        $this->call([
            ItemsTableSeeder::class,
        ]);
        // // buat kategori default
        // $audio = Category::firstOrCreate([
        //     'name' => 'Audio',
        //     'code_category'=>'AUD']);
        // $video = Category::firstOrCreate([
        //     'name' => 'Video',
        //     'code_category'=>'VID']);

        // // buat beberapa barang
        // Item::firstOrCreate(
        //     ['code' => 'MIC-0001'],
        //     [
        //         'name'        => 'Mic Shure SM58',
        //         'category_id' => $audio->id
        //     ],
        // );
        // Item::firstOrCreate(
        //     ['code' => 'CAM-0101'],
        //     [
        //         'name'        => 'Camera Sony XDCAM',
        //         'category_id' => $video->id
        //     ],
        // );

        // // buat partner contoh
        // Partner::firstOrCreate(['name' => 'Program A'], ['unit' => 'Program']);
    }
}
