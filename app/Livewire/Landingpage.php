<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Category;
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

    public $category;
    public $ad;
    public $amount = 15;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        SEOMeta::setTitle('Otim Florist Jakarta');
        SEOMeta::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
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

        OpenGraph::setTitle('Otim Florist Jakarta');
        OpenGraph::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        OpenGraph::setUrl('https://otimflorist.com');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com/img/landing.jpeg/');

        TwitterCard::setTitle('Otim Florist Jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Otim Florist Jakarta');
        JsonLd::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        JsonLd::addImage('https://otimflorist.com/img/landing.jpeg/');

        $this->category = Cache::remember('categories', 60 * 60 * 168, function () {
            return Category::all();
        });

        $this->ad = Cache::remember('ads', 60 * 60 * 168, function () {
            return Ad::all();
        });
    }

    public function render()
    {
        $products = Cache::remember("products-{$this->amount}", 60 * 60 * 168, function () {
            return Product::latest()->take($this->amount)->get();
        });

        // Add product images to OpenGraph
        if ($products->isNotEmpty()) {
            OpenGraph::addImage('https://otimflorist.com/img/favicon.png/');
        }

        return view('livewire.landingpage', [
            'title' => 'Landing Page',
            'products' => $products,
            'category' => $this->category,
            'ad' => $this->ad,
        ]);
    }

    public function load()
    {
        $this->amount += 15;
        Cache::forget("products-{$this->amount}");
    }
}
