<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;

class Bungapapancongratulation extends Component
{
    use WithPagination;

    #[Computed(cache: true)]
    #[Title('Bunga papan congratulation - Otim Florist Jakarta')]
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

        return view('livewire.bungapapancongratulation', [
            'title' => 'Bunga papan congratulation',
            'products' => $products,
            'category' => $this->category,
        ]);
    }

    public function load()
    {
        $this->amount += 10;
        Cache::forget("products-bunga-papan-congratulation-{$this->amount}");
    }

    private function getCategories()
    {
        return Cache::remember('categories', 60 * 60, function () {
            return Category::all();
        });
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-bunga-papan-congratulation-{$amount}", 60 * 60, function () use ($amount) {
            return Product::where('product_name', 'LIKE', '%bunga papan congratulations%')
                ->orWhere('product_name', 'LIKE', '%bunga papan selamat%')
                ->latest()
                ->paginate($amount);
        });
    }
}
