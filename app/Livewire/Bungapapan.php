<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Visitor;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Bungapapan extends Component
{
    use WithPagination;

    #[Computed(cache: true)]

    public $amount = 20;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        SEOMeta::setTitle('Bunga papan');
        SEOMeta::setDescription('Toko karangan bunga yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        SEOMeta::setCanonical('https://otimflorist.com/kategori/bunga-papan');
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

        OpenGraph::setDescription('bunga papan jakarta dan sekitarnya murah berkualitas');
        OpenGraph::setTitle('Jual bunga papan');
        OpenGraph::setUrl('https://otimflorist.com/kategori/bunga-papan');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com/img/landing.jpeg');

        TwitterCard::setTitle('Otim Florist Jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Jual bunga papan');
        JsonLd::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        JsonLd::addImage('https://otimflorist.com/img/landing.jpeg');

        Visitor::saveVisitor();
    }

    public function render()
    {
        $products = $this->getProducts($this->amount);

        return view('livewire.bungapapan', [
            'title' => 'Bunga papan',
            'products' => $products,
        ]);
    }

    public function load()
    {
        $this->amount += 20;
        Cache::forget("products-bunga-papan-{$this->amount}");
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-bunga-papan-{$amount}", 60 * 60 * 6, function () use ($amount) {
            return Product::where('product_name', 'LIKE', '%bp%')
                ->where('sale_price', null)
                ->orderBy('price', 'asc')
                ->paginate($amount);
        });
    }
}
