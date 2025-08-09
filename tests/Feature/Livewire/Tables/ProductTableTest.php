<?php

namespace Tests\Feature\Livewire\Tables;

use App\Livewire\Tables\ProductTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ProductTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ProductTable::class)
            ->assertStatus(200);
    }

    public function test_search_by_category_name()
    {
        $products = $this->createProducts();

        Livewire::test('tables.product-table')
            ->set('search', 'gory 1')
            ->assertSee($products->get(0)?->name)
            ->assertDontSee($products->get(1)?->name);
    }

    public function test_photo_column_is_rendered()
    {
        $this->createProduct();

        Livewire::test('tables.product-table')
            ->assertSee('Photo')
            ->assertSeeHtml('products/default.webp');
    }

    public function test_product_category_link_renders_successfully()
    {
        $product = $this->createProduct();

        Livewire::test('tables.product-table')
            ->assertSeeHtml('<a href="' . route('categories.show', $product->category->slug) . '"');
    }

    public function test_total_price_and_quantity_calculated()
    {
        $products = $this->createProducts();

        Livewire::test('tables.product-table')
            ->assertSee(number_format($products->get(0)?->quantity * $products->get(0)?->buying_price))
            ->assertSee((string) $products->sum('quantity'))
            ->assertSee(number_format($products->sum(fn($p) => $p->quantity * $p->buying_price)));
    }
}
