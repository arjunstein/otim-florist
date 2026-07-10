{{-- Spinner --}}
<div id="spinner" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-300">
    <div class="w-10 h-10 border-4 border-rose-200 border-t-rose-600 rounded-full animate-spin"></div>
</div>

<nav class="fixed top-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm" x-data="{ open: false }">
    {{-- Top bar --}}
    <div class="hidden lg:block bg-rose-600 text-white text-xs py-1.5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-6">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                Jakarta Pusat
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                otimfloristjakarta@gmail.com
            </span>
        </div>
    </div>

    {{-- Main nav --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <span class="text-2xl font-bold text-rose-600 tracking-tight">Otim Florist</span>
            </a>

            {{-- Desktop links --}}
            <div class="hidden xl:flex items-center gap-1">
                @foreach([
                    ['label' => 'Home', 'route' => '/', 'name' => null],
                    ['label' => 'Bunga Papan', 'route' => 'bungapapan', 'name' => 'bungapapan'],
                    ['label' => 'Bunga Standing', 'route' => 'bungastanding', 'name' => 'bungastanding'],
                    ['label' => 'Hand Bouquet', 'route' => 'buket', 'name' => 'buket'],
                    ['label' => 'Bunga Meja', 'route' => 'bungameja', 'name' => 'bungameja'],
                    ['label' => 'Bunga Salib', 'route' => 'bungasalib', 'name' => 'bungasalib'],
                    ['label' => 'Promo', 'route' => 'promo', 'name' => 'promo'],
                    ['label' => 'Tentang Kami', 'route' => 'tentang', 'name' => 'tentang'],
                ] as $nav)
                    @if($nav['name'])
                        <a href="{{ route($nav['route']) }}" wire:navigate
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 {{ Request::routeIs($nav['name']) ? 'text-rose-600 bg-rose-50' : 'text-gray-600 hover:text-rose-600 hover:bg-rose-50' }}">
                            {{ $nav['label'] }}
                        </a>
                    @else
                        <a href="{{ $nav['route'] }}" wire:navigate
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 {{ Request::is('/') ? 'text-rose-600 bg-rose-50' : 'text-gray-600 hover:text-rose-600 hover:bg-rose-50' }}">
                            {{ $nav['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Search + mobile toggle --}}
            <div class="flex items-center gap-2">
                <button data-bs-toggle="modal" data-bs-target="#searchModal"
                    class="p-2 rounded-full text-gray-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <button @click="open = !open" class="xl:hidden p-2 rounded-full text-gray-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-transition class="xl:hidden border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col gap-1">
            @foreach([
                ['label' => 'Home', 'route' => '/', 'name' => null],
                ['label' => 'Bunga Papan', 'route' => 'bungapapan', 'name' => 'bungapapan'],
                ['label' => 'Bunga Standing', 'route' => 'bungastanding', 'name' => 'bungastanding'],
                ['label' => 'Hand Bouquet', 'route' => 'buket', 'name' => 'buket'],
                ['label' => 'Bunga Meja', 'route' => 'bungameja', 'name' => 'bungameja'],
                ['label' => 'Bunga Salib', 'route' => 'bungasalib', 'name' => 'bungasalib'],
                ['label' => 'Promo', 'route' => 'promo', 'name' => 'promo'],
                ['label' => 'Tentang Kami', 'route' => 'tentang', 'name' => 'tentang'],
            ] as $nav)
                @if($nav['name'])
                    <a href="{{ route($nav['route']) }}" wire:navigate @click="open = false"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg {{ Request::routeIs($nav['name']) ? 'text-rose-600 bg-rose-50' : 'text-gray-700 hover:bg-gray-50' }}">
                        {{ $nav['label'] }}
                    </a>
                @else
                    <a href="{{ $nav['route'] }}" wire:navigate @click="open = false"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg {{ Request::is('/') ? 'text-rose-600 bg-rose-50' : 'text-gray-700 hover:bg-gray-50' }}">
                        {{ $nav['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</nav>

{{-- Search Modal --}}
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header border-b border-gray-100">
                <h5 class="modal-title font-semibold text-gray-800">Cari produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flex items-center">
                <div class="w-full max-w-2xl mx-auto flex gap-2">
                    <input type="search" class="flex-1 border border-gray-200 rounded-xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-rose-400 text-sm" placeholder="Cari bunga...">
                    <button class="bg-rose-600 text-white px-5 py-3.5 rounded-xl hover:bg-rose-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        const spinner = document.getElementById('spinner');
        if (spinner) {
            spinner.style.opacity = '0';
            setTimeout(() => spinner.style.display = 'none', 300);
        }
    });
</script>
