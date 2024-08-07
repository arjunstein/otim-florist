<?php

namespace App\Livewire;

use App\Models\Product;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Bungapapanselamat extends Component
{
    use WithPagination;

    #[Computed(cache: true)]

    public $amount = 10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
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
        $this->amount += 10;
        Cache::forget("products-bunga-papan-selamat{$this->amount}");
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-bunga-papan-selamat{$amount}", 60 * 60 * 168, function () use ($amount) {
            return Product::where('product_name', 'LIKE', '%bps%')
                ->latest()
                ->paginate($amount);
        });
    }
}
