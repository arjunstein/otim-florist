<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProdukDetail extends Component
{
    #[Title('Detail produk')]
    public $title = 'Detail produk';
    public $produk;
    public $category;
    public $id;
    public $slug;

    public function mount($id, $slug)
    {
        $this->id = $id;
        $this->slug = Product::where('slug', $slug)->first();
        $this->category = Category::all();
        $this->produks = Product::all();
        $this->produk = Product::findOrFail($this->id);
    }

    public function render()
    {
        return view('livewire.produk-detail', [
            'category' => $this->category,
            'produks' => $this->produks,
            'produk' => $this->produk,
            'slug' => $this->slug,
        ]);
    }
}
