<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;

class Landingpage extends Component
{
    #[Title('Otim Florist Jakarta')]
    public function render()
    {
        return view('livewire.landingpage')->with([
            'title' => 'Landing Page',
            'products' => Product::orderBy('id', 'desc')->get()
        ]);
    }
}
