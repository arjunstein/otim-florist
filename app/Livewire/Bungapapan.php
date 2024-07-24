<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

class Bungapapan extends Component
{
    #[Title('Bunga papan - Otim Florist Jakarta')]
    public $products;
    public $category;

    public function mount()
    {
        $this->products = Product::orderBy('id', 'desc')->get();
        $this->category = Category::all();
    }

    public function render()
    {
        return view('livewire.bungapapan', [
            'title' => 'Bunga papan',
            'products' => $this->products,
            'category' => $this->category,
        ]);
    }
}
