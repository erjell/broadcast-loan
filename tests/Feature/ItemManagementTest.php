<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_can_be_created(): void
    {
        $category = Category::create(['name' => 'Elektronik']);

        $response = $this->post('/items', [
            'barcode' => 'BRG001',
            'name' => 'Kamera',
            'serial_number' => 'SN123',
            'procurement_year' => 2024,
            'details' => 'DSLR',
            'category_id' => $category->id,
            'stock' => 5,
            'condition' => 'baik',
        ]);

        $response->assertSessionHas('ok', 'Barang ditambahkan');
        $this->assertDatabaseHas('items', [
            'barcode' => 'BRG001',
            'serial_number' => 'SN123',
            'procurement_year' => 2024,
        ]);
    }
}
