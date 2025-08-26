<?php

namespace Tests\Feature;

use App\Models\{Category, Item};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_stock_from_assets(): void
    {
        $category = Category::create(['name' => 'Elektronik', 'code' => 'ELE']);
        $item = Item::create([
            'name' => 'Kamera',
            'details' => 'DSLR',
            'category_id' => $category->id,
        ]);
        $item->assets()->create([
            'serial_number' => 'SN123',
            'procurement_year' => 2024,
            'condition' => 'baik',
        ]);

        $response = $this->get('/items/search?q='.$item->code);
        $response->assertJsonFragment([
            'code' => $item->code,
            'name' => 'Kamera',
            'stock' => 1,
        ]);
    }

    public function test_store_creates_item_and_asset_with_generated_codes(): void
    {
        $category = Category::create(['name' => 'Elektronik', 'code' => 'ELE']);
        $response = $this->post('/items', [
            'name' => 'Kamera',
            'details' => 'DSLR',
            'category_id' => $category->id,
            'serial_number' => 'SN123',
            'procurement_year' => 2024,
            'condition' => 'baik',
        ]);

        $response->assertRedirect('/items');
        $this->assertDatabaseHas('items', [
            'name' => 'Kamera',
            'code' => 'ELE001',
        ]);
        $this->assertDatabaseHas('assets', [
            'serial_number' => 'SN123',
            'code' => 'ELE-001',
        ]);
    }
}
