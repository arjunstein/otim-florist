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

        SEOMeta::setTitle(ucwords(str_replace('-', ' ', $slug . ' ' . ucwords($product_name))));
        SEOMeta::setDescription($this->produk->product_description);
        SEOMeta::setCanonical('https://otimflorist.com/produk/' . $slug . '/' . $product_name . '/' . $id);
        SEOMeta::addKeyword([
            "karangan bunga jakarta",
            "toko bunga jakarta",
            "bunga papan jakarta",
            "florist jakarta",
            "pesan bunga jakarta",
            "pengiriman bunga jakarta",
            "karangan bunga murah jakarta",
            "toko karangan bunga di jakarta",
            "bunga ucapan jakarta",
            "karangan bunga pernikahan jakarta",
            "karangan bunga duka jakarta",
            "buket bunga jakarta",
            "toko bunga terbaik di jakarta",
            "karangan bunga custom jakarta",
            "florist terpercaya jakarta",
            "bunga online jakarta",
            "pesan karangan bunga online jakarta",
            "toko bunga 24 jam jakarta",
            "toko bunga dekat saya jakarta",
            "bunga segar jakarta",
            "bunga papan jakarta barat",
            "bunga papan jakarta utara",
            "bunga papan jakarta timur",
            "bunga papan jakarta barat",
            "bunga standing dukacita",
            "bunga standing congratulation",
            "bunga papan dukacita",
            "toko bunga terdekat",
            "karangan bunga murah jakarta",
            "bunga salib jakarta pusat",
            "bunga salib jakarta barat",
            "bunga salib jakarta utara",
            "bunga salib jakarta timur",
            "bunga salib jakarta selatan",
            "bunga happy wedding",
            "hand buket jakarta",
            "hand bouquet jakarta",
            "bunga valentine jakarta",
            "toko bunga dekat rumah duka grand heaven",
            "toko bunga dekat rumah duka ukrida",
            "toko bunga dekat rumah duka jelambar",
            "toko bunga dekat rumah duka carolus",
            "toko bunga dekat rumah duka darmais",
            "toko bunga dekat rumah duka gatot subroto",
        ]);

        OpenGraph::setDescription($this->produk->product_description);
        OpenGraph::setTitle(ucwords(str_replace('-', ' ', $slug . ' ' . ucwords($product_name))));
        OpenGraph::setUrl('https://otimflorist.com/produk/' . $slug . '/' . $product_name . '/' . $id);
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com' . $this->produk->image);

        TwitterCard::setTitle(ucwords(str_replace('-', ' ', $slug . ' ' . ucwords($product_name))));
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle(ucwords(str_replace('-', ' ', $slug . ' ' . ucwords($product_name))));
        JsonLd::setDescription($this->produk->product_description);
        JsonLd::addImage('https://otimflorist.com' . $this->produk->image);
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
