<?php

namespace App\Livewire\Tables;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public $perPage = 25;

    public $search = '';

    public $sortField = 'id';

    public $sortAsc = false;

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;

        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'unit'])
            ->search($this->search);

        $sortMode = $this->sortAsc ? 'asc' : 'desc';

        if ($this->sortField === 'total_buying_price') {
            $products->orderByRaw('quantity * buying_price ' . $sortMode);
        } else {
            $products->orderBy($this->sortField, $sortMode);
        }

        $totals = (clone $products)
            ->selectRaw('SUM(quantity) as total_quantity, SUM(quantity * buying_price / 100) as total_buying_price')
            ->first();

        return view('livewire.tables.product-table', [
            'products' => $products->paginate($this->perPage),
            'totalQuantity' => $totals->total_quantity ?? 0,
            'totalBuyingPrice' => $totals->total_buying_price ?? 0,
        ]);
    }
}
