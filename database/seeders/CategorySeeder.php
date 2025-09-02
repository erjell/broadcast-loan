<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kamera', 'code_category' => 'CAM'],
            ['name' => 'Audio', 'code_category' => 'AUD'],
            ['name' => 'Jaringan', 'code_category' => 'NET'],
            ['name' => 'Aksesoris', 'code_category' => 'ACC'],
        ];

        foreach ($categories as $c) {
            Category::firstOrCreate(
                ['name' => $c['name']],
                ['code_category' => $c['code_category']]
            );
        }
    }
}

