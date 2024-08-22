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

class Bungapapanhappywedding extends Component
{
    use WithPagination;

    #[Computed(cache: true)]

    public $amount = 20;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        SEOMeta::setTitle('Bunga papan wedding');
        SEOMeta::setDescription('karangan bunga papan wedding terbaik di jakarta');
        SEOMeta::setCanonical('https://otimflorist.com');

        OpenGraph::setDescription('karangan bunga papan wedding jakarta terbaik');
        OpenGraph::setTitle('Bunga papan wedding terbaik di jakarta');
        OpenGraph::setUrl('https://otimflorist.com');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com/img/landing.jpeg');

        TwitterCard::setTitle('Otim Florist Jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Jual bunga papan wedding');
        JsonLd::setDescription('Bunga papan wedding jakarta');
        JsonLd::addImage('https://otimflorist.com/img/landing.jpeg');
    }

    public function render()
    {
        $products = $this->getProducts($this->amount);

        return view('livewire.bungapapanhappywedding', [
            'title' => 'Bunga papan happy wedding',
            'products' => $products,
        ]);
    }

    public function load()
    {
        $this->amount += 20;
        Cache::forget("products-bunga-papan-happy-wedding-{$this->amount}");
    }

    private function getProducts($amount)
    {
        return Cache::remember("products-bunga-papan-happy-wedding-{$amount}", 60 * 60 * 6, function () use ($amount) {
            return Product::where('product_name', 'LIKE', '%bpw%')
                ->where('sale_price', null)
                ->orderBy('price', 'asc')
                ->paginate($amount);
        });
    }
}
