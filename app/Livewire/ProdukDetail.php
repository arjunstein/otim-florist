<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ProdukDetail extends Component
{
    public $title = 'Detail produk';
    public $produk;
    public $categories;
    public $slug;
    public $product_name;
    public $products;
    public $id;

    public function mount($id, $slug, $product_name)
    {
        $this->id = $id;
        $this->slug = $slug;
        $this->product_name = $product_name;

        SEOMeta::setTitle('Otim Florist Jakarta');
        SEOMeta::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        SEOMeta::setCanonical('https://otimflorist.com');

        OpenGraph::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        OpenGraph::setTitle('Otim Florist Jakarta');
        OpenGraph::setUrl('https://otimflorist.com');
        OpenGraph::addProperty('type', 'website');

        TwitterCard::setTitle('Otim Florist Jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Otim Florist Jakarta');
        JsonLd::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        JsonLd::setImages(Storage::url('img/favicon.png'));


        $this->slug = Cache::remember("product-slug-{$slug}", 60 * 60 * 168, function () use ($slug) {
            return Product::where('slug', $slug)->first();
        });

        $this->produk = Cache::remember("product-{$this->id}", 60 * 60 * 168, function () {
            return Product::with('category')->findOrFail($this->id);
        });

        // Cache categories with product counts
        $this->categories = Cache::remember('categories_with_count', 60 * 60 * 168, function () {
            return Category::withCount('product')->get();
        });

        // Cache all products
        $this->products = Cache::remember('all_products', 60 * 60 * 168, function () {
            return Product::latest()->get();
        });
    }

    public function render()
    {
        return view('livewire.produk-detail', [
            'categories' => $this->categories,
            'products' => $this->products,
            'produk' => $this->produk,
        ]);
    }
}
