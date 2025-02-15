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

class Bungapapanselamat extends Component
{
    use WithPagination;

    #[Computed(cache: true)]

    public $amount = 20;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        SEOMeta::setTitle('Bunga papan selamat');
        SEOMeta::setDescription('Menyediakan bunga papan ucapan selamat, pelantikan, promosi, dan serah terima jabatan');
        SEOMeta::setCanonical('https://otimflorist.com/kategori/bunga-papan-selamat-dan-sukses');
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

        OpenGraph::setDescription('Menyediakan bunga papan ucapan selamat, pelantikan, promosi, dan serah terima jabatan');
        OpenGraph::setTitle('Jual bunga papan selamat');
        OpenGraph::setUrl('https://otimflorist.com/kategori/bunga-papan-selamat-dan-sukses');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com/img/landing.jpeg');

        TwitterCard::setTitle('Bunga papan selamat');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Jual bunga papan selamat');
        JsonLd::setDescription('Menyediakan bunga papan ucapan selamat, pelantikan, promosi, dan serah terima jabatan');
        JsonLd::addImage('https://otimflorist.com/img/landing.jpeg');

        Visitor::saveVisitor();
    }

    public function render()
    {
        $products = $this->getProducts($this->amount);

        return view('livewire.bungapapanselamat', [
            'title' => 'Bunga papan selamat',
            'products' => $products,
        ]);
    }

    public function load()
    {
        $this->amount += 20;
        Cache::forget("products-bunga-papan-selamat{$this->amount}");
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-bunga-papan-selamat{$amount}", 60 * 60 * 6, function () use ($amount) {
            return Product::where('product_name', 'LIKE', '%bps%')
                ->where('sale_price', null)
                ->orderBy('price', 'asc')
                ->paginate($amount);
        });
    }
}
