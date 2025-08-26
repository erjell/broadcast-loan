<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_categories(): void
    {
        Category::create(['name' => 'Elektronik']);

        $response = $this->get('/categories');
        $response->assertStatus(200);
        $response->assertSee('Elektronik');
    }

    public function test_store_creates_category(): void
    {
        $response = $this->post('/categories', ['name' => 'Furniture']);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Furniture']);
    }
}
