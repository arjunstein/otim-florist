<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Product;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Promopage extends Component
{
    use WithPagination;

    #[Computed(cache: true)]

    public $ad;
    public $amount = 20;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->ad = Cache::remember('ads-promo', 60 * 60 * 24, function () {
            return Ad::all()->first();
        });

        SEOMeta::setTitle('Promo produk pilihan');
        SEOMeta::setDescription('promo bunga jakarta segera pesan sekarang sebelum promo berakhir');
        SEOMeta::setCanonical('https://otimflorist.com/promo');
        SEOMeta::addKeyword([
            "karangan bunga jakarta",
            "promo karangan bunga murah",
            "hand bouquet murah jakarta",
            "big sale bunga murah",
            "gebyar bunga murah",
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

        OpenGraph::setDescription('Promo produk pilihan hanya hari ini saja');
        OpenGraph::setTitle(!empty($this->ad->first()->title) ? $this->ad->first()->title : 'Promo Bunga Murah | Otim Florist');
        OpenGraph::setUrl('https://otimflorist.com/promo');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(!empty($this->ad->first()->image) ? 'https://otimflorist.com/storage/' . $this->ad->first()->image : '');


        TwitterCard::setTitle('Promo bunga papan jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle(!empty($this->ad->first()->title) ? $this->ad->first()->title : 'Promo Bunga Murah | Otim Florist');
        JsonLd::setDescription('Promo produk pilihan hanya hari ini saja');
        JsonLd::addImage(!empty($this->ad->first()->image) ? 'https://otimflorist.com/storage/' . $this->ad->first()->image : '');
    }

    public function render()
    {
        $products = $this->getProducts($this->amount);

        return view('livewire.promopage', [
            'title' => 'Produk promo',
            'products' => $products,
            'ad' => $this->ad,
        ]);
    }

    public function load()
    {
        $this->amount += 20;
        Cache::forget("products-promo-{$this->amount}");
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-promo-{$amount}", 60 * 60 * 24, function () use ($amount) {
            return Product::where('sale_price', '!=', null)
                ->orderBy('sale_price', 'asc')
                ->paginate($amount);
        });
    }
}
