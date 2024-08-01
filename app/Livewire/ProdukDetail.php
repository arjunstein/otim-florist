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
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProdukDetail extends Component
{
    #[Computed(cache: true)]

    public $title = 'Detail produk';
    public $produk;
    public $categories;
    public $id;
    public $slug;

    public function mount($id, $slug)
    {
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
