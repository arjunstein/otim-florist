<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Bungasalib extends Component
{
    use WithPagination;

    #[Title('Bunga salib - Otim Florist Jakarta')]
    public $category;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->category = Category::all();
    }

    public function render()
    {
        $products =
            Product::where('product_name', 'LIKE', '%bunga salib%')
            ->orderBy('id', 'desc')
            ->paginate(8);
        return view('livewire.bungasalib', [
            'title' => ucwords('bunga salib'),
            'products' => $products,
            'category' => $this->category,
        ]);
    }
}
