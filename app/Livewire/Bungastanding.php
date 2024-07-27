<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Bungastanding extends Component
{
    use WithPagination;

    #[Title('Bunga standing - Otim Florist Jakarta')]
    public $category;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->category = Category::all();
    }

    public function render()
    {
        $products =
            Product::where('product_name', 'LIKE', '%bunga standing%')
            ->orderBy('id', 'desc')
            ->paginate(8);
        return view('livewire.bungastanding', [
            'title' => ucwords('bunga standing'),
            'products' => $products,
            'category' => $this->category,
        ]);
    }
}
