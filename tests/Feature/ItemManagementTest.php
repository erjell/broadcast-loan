<?php

namespace Tests\Feature;

use App\Models\{Category, Item};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_items(): void
    {
        $category = Category::create(['name' => 'Elektronik', 'code_category' => 'ELK']);
        $item = Item::create([
            'name' => 'Kamera',
            'details' => 'DSLR',
            'category_id' => $category->id,
            'serial_number' => 'SN123',
            'procurement_year' => 2024,
            'condition' => 'baik',
        ]);

        $response = $this->get('/items/search?q='.$item->code);
        $response->assertJsonFragment([
            'code' => $item->code,
            'name' => 'Kamera',
        ]);
    }

    public function test_search_by_serial_number_returns_item(): void
    {
        $category = Category::create(['name' => 'Elektronik', 'code_category' => 'ELK']);
        $item = Item::create([
            'name' => 'Kamera',
            'details' => 'DSLR',
            'category_id' => $category->id,
            'serial_number' => 'SN123',
            'procurement_year' => 2024,
            'condition' => 'baik',
        ]);

        $response = $this->get('/items/search?q='.$item->serial_number);
        $response->assertJsonFragment([
            'serial_number' => 'SN123',
            'name' => 'Kamera',
        ]);
    }

    public function test_store_creates_item_with_generated_code(): void
    {
        $category = Category::create(['name' => 'Elektronik', 'code_category' => 'ELK']);
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
            'code' => 'ELK001',
            'serial_number' => 'SN123',
        ]);
    }
}
