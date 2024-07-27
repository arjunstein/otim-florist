<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

class Bungastanding extends Component
{
    #[Title('Bunga standing - Otim Florist Jakarta')]
    public $products;
    public $category;

    public function mount()
    {
        $this->products =
            Product::where('product_name', 'LIKE', '%bunga standing%')
            ->orderBy('id', 'desc')
            ->get();
        $this->category = Category::all();
    }

    public function render()
    {
        return view('livewire.bungastanding', [
            'title' => ucwords('bunga standing'),
            'products' => $this->products,
            'category' => $this->category,
        ]);
    }
}
