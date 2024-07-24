<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimoni;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class Landingpage extends Component
{
    use WithPagination;

    #[Title('Otim Florist Jakarta')]
    public $category;
    public $testimoni;
    public $ad;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->category = Category::all();
        $this->testimoni = Testimoni::all();
        $this->ad = Ad::all();
    }

    public function render()
    {
        $products = Product::orderBy('id', 'desc')->paginate(8);
        return view('livewire.landingpage', [
            'title' => 'Landing Page',
            'products' => $products,
            'category' => $this->category,
            'testimoni' => $this->testimoni,
            'ad' => $this->ad,
        ]);
    }
}
