<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Landingpage extends Component
{
    public function render()
    {
        return view('livewire.landingpage')->with([
            'title' => 'Landing Page',
            'products' => Product::orderBy('id', 'desc')->get()
        ]);
    }
}
