<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Category, Item};

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori ada
        $catCam = Category::firstOrCreate(['name' => 'Kamera'], ['code_category' => 'CAM']);
        $catAud = Category::firstOrCreate(['name' => 'Audio'], ['code_category' => 'AUD']);
        $catNet = Category::firstOrCreate(['name' => 'Jaringan'], ['code_category' => 'NET']);
        $catAcc = Category::firstOrCreate(['name' => 'Aksesoris'], ['code_category' => 'ACC']);

        $items = [
            // Kamera
            ['name' => 'Sony FX6',           'details' => 'Kamera sinema 4K',                 'serial_number' => 'FX6-001', 'procurement_year' => 2023, 'condition' => 'baik', 'category_id' => $catCam->id],
            ['name' => 'Sony A7SIII',        'details' => 'Mirrorless low light',             'serial_number' => 'A7S3-002','procurement_year' => 2022, 'condition' => 'baik', 'category_id' => $catCam->id],
            ['name' => 'Panasonic GH6',      'details' => 'Mirrorless micro four thirds',     'serial_number' => 'GH6-003', 'procurement_year' => 2024, 'condition' => 'baik', 'category_id' => $catCam->id],
            // Audio
            ['name' => 'Shure SM7B',         'details' => 'Mic broadcast dinamik',            'serial_number' => 'SM7B-101','procurement_year' => 2021, 'condition' => 'baik', 'category_id' => $catAud->id],
            ['name' => 'Rode Wireless GO II','details' => 'Mic wireless dual channel',        'serial_number' => 'RWG2-102','procurement_year' => 2023, 'condition' => 'baik', 'category_id' => $catAud->id],
            ['name' => 'Zoom H6',            'details' => 'Portable recorder 6 input',        'serial_number' => 'H6-103',  'procurement_year' => 2020, 'condition' => 'baik', 'category_id' => $catAud->id],
            // Jaringan
            ['name' => 'MikroTik hAP ac2',   'details' => 'Router WiFi AC dual band',         'serial_number' => 'HAP2-201','procurement_year' => 2022, 'condition' => 'baik', 'category_id' => $catNet->id],
            ['name' => 'Ubiquiti NanoStation','details'=> 'CPE outdoor 5GHz',                  'serial_number' => 'NS5-202', 'procurement_year' => 2021, 'condition' => 'baik', 'category_id' => $catNet->id],
            ['name' => 'Netgear GS308',      'details' => 'Switch 8 port gigabit',            'serial_number' => 'GS308-203','procurement_year' => 2023, 'condition' => 'baik', 'category_id' => $catNet->id],
            // Aksesoris
            ['name' => 'Tripod Manfrotto',   'details' => 'Tripod aluminium',                 'serial_number' => 'TRI-301', 'procurement_year' => 2019, 'condition' => 'baik', 'category_id' => $catAcc->id],
            ['name' => 'Monopod',            'details' => 'Monopod ringan',                   'serial_number' => 'MONO-302','procurement_year' => 2018, 'condition' => 'rusak_ringan', 'category_id' => $catAcc->id],
            ['name' => 'HDMI Splitter 1x4',  'details' => 'Pembagi HDMI 4 port',              'serial_number' => 'HDMI-303','procurement_year' => 2025, 'condition' => 'baik', 'category_id' => $catAcc->id],
        ];

        foreach ($items as $row) {
            // Biarkan kode item ter-generate otomatis: {CODE_CATEGORY}{running}
            Item::firstOrCreate(
                [
                    'name' => $row['name'],
                    'serial_number' => $row['serial_number'],
                ],
                $row
            );
        }
    }
}

