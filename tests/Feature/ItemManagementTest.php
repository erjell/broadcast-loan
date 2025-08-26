<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_created_and_code_displayed_on_index(): void
    {
        $category = Category::create(['name' => 'Video', 'prefix' => 'AV']);

        $this->post('/items', [
            'name' => 'Camera',
            'category_id' => $category->id,
            'details' => '4K',
        ])->assertSessionHas('ok', 'Item ditambahkan');

        $this->get('/items')
            ->assertSee('AV001')
            ->assertSee('Camera');
    }
}
