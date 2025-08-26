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
        $category = Category::create(['name' => 'Elektronik']);
        $item = Item::create([
            'name' => 'Kamera',
            'details' => 'DSLR',
            'category_id' => $category->id,
        ]);
        $item->assets()->create([
            'code' => $item->code.'-001',
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
}
