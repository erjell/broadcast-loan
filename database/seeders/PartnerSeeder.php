<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Bagian Program',    'unit' => 'Program',    'phone' => '0812-0000-0001'],
            ['name' => 'Bagian Pemberitaan','unit' => 'Pemberitaan','phone' => '0812-0000-0002'],
            ['name' => 'Bagian Produksi',   'unit' => 'Produksi',   'phone' => '0812-0000-0003'],
            ['name' => 'Eksternal A',       'unit' => 'Eksternal',  'phone' => '0812-0000-0004'],
        ];

        foreach ($partners as $p) {
            Partner::firstOrCreate(['name' => $p['name']], $p);
        }
    }
}

