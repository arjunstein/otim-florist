<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Bungameja extends Component
{
    use WithPagination;

    #[Title('Bunga meja - Otim Florist Jakarta')]
    public $category;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->category = Category::all();
    }

    public function render()
    {
        $products =
            Product::where('product_name', 'LIKE', '%bunga meja%')
            ->orderBy('id', 'desc')
            ->paginate(8);
        return view('livewire.bungameja', [
            'title' => ucwords('bunga meja'),
            'products' => $products,
            'category' => $this->category,
        ]);
    }
}
