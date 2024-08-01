<?php

namespace App\Livewire;

use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Tentang extends Component
{
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
        return view('livewire.tentang');
    }
}
