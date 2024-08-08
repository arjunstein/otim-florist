<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Product;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class Landingpage extends Component
{
    use WithPagination;

    #[Computed(cache: true)]

    public $ad;
    public $amount = 20;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        SEOMeta::setTitle('Otim Florist Jakarta');
        SEOMeta::setDescription('Jual bunga papan, bunga standing, bunga meja, bunga pengantin, bunga salib');
        SEOMeta::setCanonical('https://otimflorist.com');
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

        OpenGraph::setTitle('Jual bunga papan');
        OpenGraph::setDescription('Jual bunga papan, bunga standing, bunga meja, bunga pengantin, bunga salib');
        OpenGraph::setUrl('https://otimflorist.com');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com/img/front.webp');

        TwitterCard::setTitle('Otim Florist Jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Jual bunga papan');
        JsonLd::setDescription('Jual bunga papan, bunga standing, bunga meja, bunga pengantin, bunga salib');
        JsonLd::addImage('https://otimflorist.com/img/front.webp/');

        $this->ad = Cache::remember('ads', 60 * 60 * 24, function () {
            return Ad::all();
        });
    }

    public function render()
    {
        $cachedView = Cache::remember("landingpage-html-{$this->amount}", 60 * 60 * 24, function () {
            $products = Cache::remember("products-{$this->amount}", 60 * 60 * 24, function () {
                return Product::where('product_name', 'LIKE', '%bp%')
                    ->where('sale_price', null)
                    ->orderBy('price', 'asc')->take($this->amount)->get();
            });

            return view('livewire.landingpage', [
                'title' => 'Landing Page',
                'products' => $products,
                'ad' => $this->ad,
            ])->render();
        });

        return $cachedView;
    }

    public function load()
    {
        $this->amount += 20;
        Cache::forget("products-{$this->amount}");
        Cache::forget("landingpage-html-{$this->amount}");
    }
}
