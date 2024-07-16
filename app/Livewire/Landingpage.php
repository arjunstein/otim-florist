<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;

class Landingpage extends Component
{
    #[Title('Otim Florist Jakarta')]
    public $title = 'Landing page';
    public $products;
    public $category;

    public function mount()
    {
        $this->products = Product::orderBy('id', 'desc')->get();
        $this->category = Category::all();
    }

    public function render()
    {
        return view('livewire.landingpage', [
            'title' => 'Landing Page',
            'products' => $this->products,
            'category' => $this->category,
        ]);
    }
}
