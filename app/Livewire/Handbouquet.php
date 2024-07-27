<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class Handbouquet extends Component
{
    use WithPagination;

    #[Title('Hand bouquet - Otim Florist Jakarta')]
    public $category;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->category = Category::all();
    }

    public function render()
    {
        $products =
            Product::where('product_name', 'LIKE', '%hand bouquet%')
            ->orderBy('id', 'desc')
            ->paginate(8);
        return view('livewire.handbouquet', [
            'title' => ucwords('hand bouquet'),
            'products' => $products,
            'category' => $this->category,
        ]);
    }
}
