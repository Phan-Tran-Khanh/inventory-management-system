<?php

namespace Tests;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, InteractsWithExceptionHandling;

    public function createUser()
    {
        return User::factory()->create([
           'name' => 'admin',
           'email' => 'admin@admin.com'
        ]);
    }

    public function createProduct()
    {
        return Product::factory()->create([
            'name' => 'Test Product',
            'category_id' => $this->createCategory(),
            'unit_id' => $this->createUnit()
        ]);
    }

    public function createProducts(int $count = 2, int $cntCategories = 2, int $cntUnits = 2)
    {
        $categories = $this->createCategories($cntCategories);
        $units = $this->createUnits($cntUnits);

        $productsData = [];

        for ($i = 0; $i < $count; $i++) {
            $productsData[] = [
                'name' => 'Product ' . ($i + 1),
                'category_id' => $categories[$i % $cntCategories]->id,
                'unit_id' => $units[$i % $cntUnits]->id,
            ];
        }

        return Product::factory()->createMany($productsData);
    }

    protected function createCategory()
    {
        return Category::factory()->create([
            'name' => 'Speakers'
        ]);
    }

    protected function createCategories(int $count = 2)
    {
        $categoriesData = [];

        for ($i = 0; $i < $count; $i++) {
            $categoriesData[] = [
                'name' => 'Category ' . ($i + 1),
            ];
        }

        return Category::factory()->createMany($categoriesData);
    }

    protected function createUnit()
    {
        return Unit::factory()->create([
            'name' => 'piece'
        ]);
    }

    protected function createUnits(int $count = 2)
    {
        $unitsData = [];

        for ($i = 0; $i < $count; $i++) {
            $unitsData[] = [
                'name' => 'Unit ' . ($i + 1),
            ];
        }

        return Unit::factory()->createMany($unitsData);
    }

    public function createCustomer()
    {
        return Customer::factory()->create([
            'name' => 'Customer 1'
        ]);
    }

    public function createSupplier()
    {
        return Supplier::create([
            'name' => 'Thomann'
        ]);
    }
}
