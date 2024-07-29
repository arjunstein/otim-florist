<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimoni;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class Landingpage extends Component
{
    use WithPagination;

    #[Computed(cache: true)]
    #[Title('Otim Florist Jakarta')]
    public $category;
    public $testimoni;
    public $ad;
    public $amount = 15;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->category = Cache::remember('categories', 60 * 60, function () {
            return Category::all();
        });

        $this->testimoni = Cache::remember('testimonis', 60 * 60, function () {
            return Testimoni::all();
        });

        $this->ad = Cache::remember('ads', 60 * 60, function () {
            return Ad::all();
        });
    }

    public function render()
    {
        $products = Cache::remember("products-{$this->amount}", 60 * 60, function () {
            return Product::take($this->amount)->get();
        });

        return view('livewire.landingpage', [
            'title' => 'Landing Page',
            'products' => $products,
            'category' => $this->category,
            'testimoni' => $this->testimoni,
            'ad' => $this->ad,
        ]);
    }

    public function load()
    {
        $this->amount += 15;
        Cache::forget("products-{$this->amount}");
    }
}
