<?php

namespace App\Livewire;

use App\Models\Subscriber;
use App\Models\Visitor;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Tentang extends Component
{
    public $fullname = '';
    public $email = '';
    public $messages = '';

    public function subscriber()
    {
        // Lama sesi
        $cooldownPeriod = 60 * 60 * 1;

        if (session()->has('last_submitted')) {
            $lastSubmitted = session('last_submitted');
            $timeSinceLastSubmission = time() - $lastSubmitted;
            $sisaSesi = $cooldownPeriod - $timeSinceLastSubmission;

            if ($timeSinceLastSubmission < $cooldownPeriod) {
                session()->flash('oops', "Tunggu {$sisaSesi} detik sebelum mengirim pesan lagi.");
                return;
            }
        }

        $validatedData = $this->validate([
            'fullname' => 'required|min:3|max:30|string',
            'email' => 'required|email|unique:subscribers,email|max:50',
            'messages' => 'required|min:3|max:200|string',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        $validatedData['fullname'] = strip_tags($validatedData['fullname']);
        $validatedData['email'] = filter_var($validatedData['email'], FILTER_SANITIZE_EMAIL);
        $validatedData['messages'] = strip_tags($validatedData['messages']);

        Subscriber::create($validatedData);
        session(['last_submitted' => time()]);

        session()->flash('status', 'Pesan berhasil dikirim');
        $this->reset(['fullname', 'email', 'messages']);
    }

    public function mount()
    {
        SEOMeta::setTitle('Tentang kami');
        SEOMeta::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        SEOMeta::setCanonical('https://otimflorist.com/tentang-kami');

        OpenGraph::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        OpenGraph::setTitle('Tentang Otim Florist');
        OpenGraph::setUrl('https://otimflorist.com/tentang-kami');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('https://otimflorist.com/img/favicon.png');

        TwitterCard::setTitle('Otim Florist Jakarta');
        TwitterCard::setSite('@otimfloristjakarta');

        JsonLd::setTitle('Tentang Otim Florist');
        JsonLd::setDescription('Toko bunga online yang menawarkan berbagai macam bunga segar untuk berbagai acara seperti ulang tahun, pernikahan, dan hari spesial lainnya. Pilih dari berbagai buket dan karangan bunga yang cantik dan menawan');
        JsonLd::addImage('https://otimflorist.com/img/favicon.png');

        Visitor::saveVisitor();
    }
    public function render()
    {
        return view('livewire.tentang');
    }
}
