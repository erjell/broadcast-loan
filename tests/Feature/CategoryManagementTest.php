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
        Category::create(['name' => 'Elektronik', 'code' => 'ELE']);

        $response = $this->get('/categories');
        $response->assertStatus(200);
        $response->assertSee('Elektronik');
        $response->assertSee('ELE');
    }

    public function test_create_displays_form(): void
    {
        $response = $this->get('/categories/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Kategori');
        $response->assertSee('Kode Kategori');
        $response->assertSee('Nama Kategori');
    }

    public function test_store_creates_category(): void
    {
        $response = $this->post('/categories', ['name' => 'Furniture', 'code' => 'FUR']);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Furniture', 'code' => 'FUR']);
    }
}
