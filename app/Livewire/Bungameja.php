<?php

namespace App\Livewire;

use App\Models\Product;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Bungameja extends Component
{
    use WithPagination;

    #[Computed(cache: true)]

    public $amount = 10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        SEOMeta::setTitle('Bunga meja');
        SEOMeta::setDescription('Menyediakan bunga meja untuk acara kantor dan sebagainya');
        SEOMeta::setCanonical('https://otimflorist.com/kategori/bunga-meja');
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

        OpenGraph::setDescription('Menyediakan bunga meja untuk acara kantor dan sebagainya');
        OpenGraph::setTitle('Jual bunga meja');
        OpenGraph::setUrl('https://otimflorist.com/kategori/bunga-meja');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com/img/front.webp');

        TwitterCard::setTitle('Otim Florist Jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Jual bunga meja');
        JsonLd::setDescription('Menyediakan bunga meja untuk acara kantor dan sebagainya');
        OpenGraph::addImage('https://otimflorist.com/img/front.webp');
    }

    public function render()
    {
        $products = $this->getProducts($this->amount);

        return view('livewire.bungameja', [
            'title' => 'Bunga meja',
            'products' => $products,
        ]);
    }

    public function load()
    {
        $this->amount += 20;
        Cache::forget("products-bunga-meja-{$this->amount}");
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-bunga-meja-{$amount}", 60 * 60 * 24, function () use ($amount) {
            return Product::where('product_name', 'LIKE', '%bm%')
                ->where('sale_price', null)
                ->orderBy('price', 'asc')
                ->paginate($amount);
        });
    }
}
