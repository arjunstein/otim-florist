<div class="font-sans">

    {{-- ===== HERO ===== --}}
    <section class="relative bg-gradient-to-br from-rose-50 via-white to-pink-50 pt-32 pb-16 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-rose-100 rounded-full opacity-40 blur-3xl"></div>
            <div class="absolute bottom-0 -left-24 w-72 h-72 bg-pink-100 rounded-full opacity-40 blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="flex-1 text-center lg:text-left">
                    <span class="inline-block bg-rose-100 text-rose-600 text-sm font-semibold px-4 py-1.5 rounded-full mb-4">
                        🌸 100% Berkualitas & Fresh
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Terbuat dari <span class="text-rose-600">bunga pilihan</span> yang segar
                    </h1>
                    <p class="text-gray-500 text-lg mb-8 max-w-lg mx-auto lg:mx-0">
                        Rangkaian bunga indah untuk setiap momen spesial Anda. Dipesan mudah, dikirim cepat, dijamin memuaskan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('bungapapan') }}" wire:navigate class="btn-primary shadow-lg shadow-rose-200">
                            Lihat Produk
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="https://wa.me/6281808881477?text=Hi%20saya%20ingin%20konsultasi%20bunga" target="_blank" class="btn-outline">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.135.557 4.135 1.535 5.875L0 24l6.311-1.508A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.96 0-3.793-.525-5.368-1.437l-.385-.228-3.986.952.97-3.886-.251-.4A9.944 9.944 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                            WhatsApp Kami
                        </a>
                    </div>
                </div>

                {{-- Carousel --}}
                <div class="flex-1 w-full max-w-lg">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl aspect-[4/3]" id="heroCarousel">
                        @forelse ($ad as $i => $ads)
                            <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $i == 0 ? 'opacity-100' : 'opacity-0' }}">
                                <img src="{{ asset('storage/' . $ads->image) }}"
                                    class="w-full h-full object-cover"
                                    alt="{{ $ads->title }}">
                                @if ($ads->url)
                                    <a href="{{ url($ads->url) }}" class="absolute inset-0" target="_blank"></a>
                                @else
                                    <a href="{{ route('promo') }}" class="absolute inset-0" wire:navigate></a>
                                @endif
                            </div>
                        @empty
                            <div class="flex items-center justify-center h-full bg-rose-50">
                                <span class="text-6xl">🌸</span>
                            </div>
                        @endforelse

                        @if ($ad->isNotEmpty() && $ad->count() > 1)
                            <button onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 backdrop-blur rounded-full flex items-center justify-center shadow hover:bg-white transition">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 backdrop-blur rounded-full flex items-center justify-center shadow hover:bg-white transition">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURES ===== --}}
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['icon' => '🚗', 'title' => 'Free Ongkir', 'desc' => 'Area Jakarta Pusat & Jakarta Barat'],
                    ['icon' => '🔒', 'title' => 'Pembayaran Aman', 'desc' => '100% amanah via transfer bank'],
                    ['icon' => '🏆', 'title' => 'Profesional', 'desc' => 'Pengalaman lebih dari 10 tahun'],
                    ['icon' => '📞', 'title' => '24/7 Fast Response', 'desc' => 'Siap melayani kapan saja'],
                ] as $feat)
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-rose-50 hover:bg-rose-100 transition-colors duration-200">
                    <span class="text-4xl mb-3">{{ $feat['icon'] }}</span>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $feat['title'] }}</h3>
                    <p class="text-sm text-gray-500">{{ $feat['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== PRODUK POPULER ===== --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <p class="text-rose-600 font-semibold text-sm mb-1">Koleksi Terbaik</p>
                    <h2 class="text-3xl font-bold text-gray-900">Produk Populer</h2>
                </div>
                <a href="{{ route('bungapapan') }}" wire:navigate class="text-rose-600 hover:text-rose-700 font-medium text-sm flex items-center gap-1">
                    Lihat semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                @forelse ($products as $product)
                    <div class="card-product" wire:key="{{ $product->id }}">
                        <div class="relative overflow-hidden aspect-square">
                            <a href="{{ route('product.detail', ['slug' => $product->slug, 'product_name' => Str::slug($product->product_name), 'id' => $product->id]) }}" wire:navigate>
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                    alt="{{ $product->product_name }}">
                            </a>
                            @if(isset($product->discount))
                                <span class="absolute top-3 left-3 bg-rose-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                    PROMO
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 text-sm leading-snug mb-2 line-clamp-2">{{ $product->product_name }}</h3>
                            <div class="flex flex-col gap-1 mb-3">
                                @if(isset($product->discount))
                                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price) }}</span>
                                    <span class="text-base font-bold text-rose-600">Rp {{ number_format($product->sale_price) }}</span>
                                @else
                                    <span class="text-base font-bold text-gray-900">Rp {{ number_format($product->price) }}</span>
                                @endif
                            </div>
                            <a href="{{ route('order', ['id' => Str::slug($product->id)]) }}"
                                target="_blank"
                                class="w-full flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.135.557 4.135 1.535 5.875L0 24l6.311-1.508A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.96 0-3.793-.525-5.368-1.437l-.385-.228-3.986.952.97-3.886-.251-.4A9.944 9.944 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                                Pesan
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-16 text-gray-400">
                        <span class="text-5xl block mb-4">🌸</span>
                        Produk belum tersedia
                    </div>
                @endforelse
            </div>

            <div class="flex justify-center mt-10">
                <button wire:click="load" class="btn-outline">
                    Muat lebih banyak
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
        </div>
    </section>

    {{-- ===== TESTIMONIAL ===== --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-rose-600 font-semibold text-sm mb-1">Ulasan Pelanggan</p>
                <h2 class="text-3xl font-bold text-gray-900">Kata Mereka</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['quote' => 'Terimakasih atas bantuannya! Otim Florist pelayanan cepat, proses cepat, kualitas oke!', 'name' => 'Sisca Setya Evi', 'company' => 'PT. Suprajaya Duaribu Satu', 'img' => 'img/supra.png'],
                    ['quote' => 'Terimakasih atas bantuannya! Otim Florist pelayanan cepat, proses cepat, kualitas oke!', 'name' => 'Andes Jampang', 'company' => 'PT. BPR Mitra Daya Mandiri', 'img' => 'img/bpr-logo.png'],
                    ['quote' => 'Bagus dan pelayanan cepat. Owner ramah dan baik. Selalu berlangganan di sini. Terimakasih 💕', 'name' => 'Putri Sagala', 'company' => 'BBKSDA Sumatera Utara', 'img' => 'img/bbksda-sumut.png'],
                    ['quote' => 'Fast response dan memberikan layanan terbaik untuk setiap customer.', 'name' => 'Maria Adriana', 'company' => 'Datascript Business Solution', 'img' => 'img/datascript.png'],
                    ['quote' => 'Sudah langganan di toko ini. Penjual sangat ramah, bunga masih segar, hiasannya sangat rapi, amanah.', 'name' => 'Alex Waskito', 'company' => 'PT. Jalaprima Perkasa', 'img' => 'img/jalaprima.png'],
                ] as $t)
                <div class="bg-gray-50 rounded-2xl p-6 flex flex-col gap-4 hover:shadow-md transition-shadow duration-200">
                    <svg class="w-8 h-8 text-rose-300" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="text-gray-600 text-sm leading-relaxed flex-1">{{ $t['quote'] }}</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <img src="{{ asset($t['img']) }}" class="w-12 h-12 rounded-full object-cover bg-white border border-gray-100" alt="{{ $t['name'] }}">
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $t['name'] }}</p>
                            <p class="text-xs text-gray-400">{{ $t['company'] }}</p>
                        </div>
                        <a href="https://g.page/r/CUwQGqLxue8DEAE/review" target="_blank" class="ml-auto text-xs text-rose-500 hover:text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg>
                            Review
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</div>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    function showSlide(n) {
        slides.forEach(s => s.classList.replace('opacity-100', 'opacity-0'));
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.replace('opacity-0', 'opacity-100');
    }
    function nextSlide() { showSlide(currentSlide + 1); }
    function prevSlide() { showSlide(currentSlide - 1); }
    if (slides.length > 1) setInterval(nextSlide, 4000);
</script>
