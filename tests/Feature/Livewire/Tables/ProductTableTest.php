<?php

namespace Tests\Feature\Livewire\Tables;

use App\Livewire\Tables\ProductTable;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ProductTableTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ProductTable::class)
            ->assertStatus(200);
    }

    public function test_search_by_category_name()
    {
        Unit::factory()->count(3)->create();

        $category1 = Category::factory()->create(['name' => 'Smartphone']);
        $category2 = Category::factory()->create(['name' => 'Earphone']);

        $product1 = Product::factory()->create(['name' => 'My Phone', 'category_id' => $category1->id]);
        $product2 = Product::factory()->create(['name' => 'My Earphone', 'category_id' => $category2->id]);

        Livewire::test('tables.product-table')
            ->set('search', 'Smart')
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);
    }
}
