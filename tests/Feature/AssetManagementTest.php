<?php

namespace Tests\Feature;

use App\Models\{Category, Item};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_code_generated_per_category(): void
    {
        $category = Category::create(['name' => 'Video', 'prefix' => 'AV']);
        $item = Item::create(['name' => 'AV Matrix SDI to HDMI', 'category_id' => $category->id, 'stock' => 0]);

        $this->post('/items', [
            'item_id' => $item->id,
            'serial_number' => 'SN123',
            'procurement_year' => 2024,
            'condition' => 'baik',
        ])->assertSessionHas('ok', 'Aset ditambahkan');

        $this->assertDatabaseHas('assets', [
            'item_id' => $item->id,
            'serial_number' => 'SN123',
            'code' => 'AV001',
        ]);
        $this->assertEquals(1, $item->fresh()->stock);

        // second asset should increment code
        $this->post('/items', [
            'item_id' => $item->id,
            'serial_number' => 'SN124',
            'procurement_year' => 2024,
            'condition' => 'baik',
        ]);
        $this->assertDatabaseHas('assets', [
            'code' => 'AV002',
        ]);
    }
}
