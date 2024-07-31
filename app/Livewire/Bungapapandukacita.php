<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;

class Bungapapandukacita extends Component
{
    use WithPagination;

    #[Computed(cache: true)]
    #[Title('Bunga papan dukacita - Otim Florist Jakarta')]
    public $category;
    public $amount = 10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->category = $this->getCategories();
    }

    public function render()
    {
        $products = $this->getProducts($this->amount);

        return view('livewire.bungapapandukacita', [
            'title' => 'Bunga papan dukacita',
            'products' => $products,
            'category' => $this->category,
        ]);
    }

    public function load()
    {
        $this->amount += 10;
        Cache::forget("products-bunga-papan-dukacita-{$this->amount}");
    }

    private function getCategories()
    {
        return Cache::remember('categories', 60 * 60, function () {
            return Category::all();
        });
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-bunga-papan-dukacita-{$amount}", 60 * 60, function () use ($amount) {
            return Product::where('product_name', 'LIKE', '%bunga papan dukacita%')
                ->latest()
                ->paginate($amount);
        });
    }
}
