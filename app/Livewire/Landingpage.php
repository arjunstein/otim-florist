<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimoni;
use Livewire\Component;
use Livewire\Attributes\Title;

class Landingpage extends Component
{
    #[Title('Otim Florist Jakarta')]
    public $products;
    public $category;
    public $testimoni;
    public $ad;

    public function mount()
    {
        $this->products = Product::orderBy('id', 'desc')->get();
        $this->category = Category::all();
        $this->testimoni = Testimoni::all();
        $this->ad = Ad::all();
    }

    public function render()
    {
        return view('livewire.landingpage', [
            'title' => 'Landing Page',
            'products' => $this->products,
            'category' => $this->category,
            'testimoni' => $this->testimoni,
            'ad' => $this->ad,
        ]);
    }
}
