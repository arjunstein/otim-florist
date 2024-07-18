<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Tentang extends Component
{
    #[Title('Tentang kami')]

    public function render()
    {
        return view('livewire.tentang');
    }
}
