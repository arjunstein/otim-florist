<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;

class ProdukDetail extends Component
{
    #[Computed(cache: true)]
    #[Title('Detail produk')]
    public $title = 'Detail produk';
    public $produk;
    public $categories;
    public $id;
    public $slug;

    public function mount($id, $slug)
    {
        $this->id = $id;

        // Cache data berdasarkan slug produk
        $this->slug = Cache::remember("product-slug-{$slug}", 60 * 60, function () use ($slug) {
            return Product::where('slug', $slug)->first();
        });

        // Cache kategori dengan jumlah produk
        $this->categories = Cache::remember('categories_with_count', 60 * 60, function () {
            return Category::withCount('product')->get()->map(function ($category) {
                $category->slug = $category->slug; // generate slug
                return $category;
            });
        });

        // Cache semua produk
        $this->products = Cache::remember('products', 60 * 60, function () {
            return Product::latest()->get();
        });

        // Cache detail produk berdasarkan ID
        $this->produk = Cache::remember("product-{$this->id}", 60 * 60, function () {
            return Product::with('category')->findOrFail($this->id);
        });
    }

    public function render()
    {
        return view('livewire.produk-detail', [
            'categories' => $this->categories,
            'products' => $this->products,
            'produk' => $this->produk,
            'slug' => $this->slug,
        ]);
    }
}
