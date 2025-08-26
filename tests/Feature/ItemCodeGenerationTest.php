<?php

namespace Tests\Feature;

use App\Models\{Category, Item};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemCodeGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_code_generated_per_category(): void
    {
        $video = Category::create(['name' => 'Video', 'prefix' => 'AV']);
        $audio = Category::create(['name' => 'Audio', 'prefix' => 'AU']);

        $first = Item::create(['name' => 'Camera', 'category_id' => $video->id]);
        $second = Item::create(['name' => 'Switcher', 'category_id' => $video->id]);
        $third = Item::create(['name' => 'Mic', 'category_id' => $audio->id]);

        $this->assertEquals('AV001', $first->code);
        $this->assertEquals('AV002', $second->code);
        $this->assertEquals('AU001', $third->code);
    }
}
