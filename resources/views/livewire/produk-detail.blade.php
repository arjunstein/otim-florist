<div>
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">{{ $title }}</h1>
    </div>
    <!-- Single Page Header End -->
    <div class="container-fluid py-5 mt-2">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <a href="#">
                                    <img src="{{ asset('storage/' . $produk->image) }}" class="img-fluid rounded"
                                        alt="Image">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3">{{ $produk->product_name }}</h4>
                            <p class="mb-3">Kategori: {{ ucfirst($produk->category->category_name) }}</p>
                            <h5 class="fw-bold text-danger mb-3 text-decoration-line-through">
                                {{ isset($produk->sale_price) ? 'Rp. ' . number_format($produk->price) : '' }}</h5>
                            <h5 class="fw-bold text-success mb-3">Rp.
                                {{ isset($produk->sale_price) ? number_format($produk->sale_price) : number_format($produk->price) }}
                            </h5>
                            <p class="mb-4">{{ $produk->product_description }}</p>
                            <a href="{{ route('order', ['id' => Str::slug($produk->id)]) }}" target="_blank"
                                class="order btn border btn-primary rounded-pill px-3 text-white">
                                <i class="fab fa-whatsapp me-2 text-white"></i> Beli</a>

                            <button data-bs-toggle="modal" data-bs-target="#exampleModal"
                                class="share-socmed btn border btn-primary rounded-pill px-3 text-white">
                                <i class="fas fa-share me-2 text-white"></i> Bagikan</button>

                            <div class="modal" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Bagikan ke</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body mx-auto">
                                            <!-- Facebook Share -->
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                                target="_blank" class="btn btn-facebook"
                                                rel="nofollow noopener noreferrer">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                            <!-- Telegram sharing via URL) -->
                                            <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}"
                                                class="btn btn-telegram"><i class="fab fa-telegram"></i></a>
                                            <!-- WhatsApp Share -->
                                            <a href="https://wa.me/?text={{ urlencode(url()->current()) }}"
                                                target="_blank" class="btn btn-whatsapp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                            <!-- LinkedIn Share -->
                                            <a href="https://www.linkedin.com/sharing/share-offsite?mini=true&url={{ urlencode(url()->current()) }}"
                                                target="_blank" class="btn btn-linkedin" rel="follow">
                                                <i class="fab fa-linkedin"></i>
                                            </a>
                                            <!-- Twitter Share -->
                                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($produk->product_name) }}"
                                                target="_blank" class="btn btn-twitter">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="row g-4 fruite">
                        <div class="col-lg-12">
                            <div class="mb-4">
                                <h4>Kategori produk</h4>
                                <ul class="list-unstyled fruite-categorie">
                                    @foreach ($categories as $category)
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="{{ url('/kategori/' . $category->slug) }}" wire:navigate><i
                                                        class="fas fa-store me-2"></i>{{ $category->category_name }}</a>
                                                <span>{{ $category->product_count }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="fw-bold mb-0">Produk lainnya</h1>
            <div class="vesitable">
                <div class="owl-carousel vegetable-carousel justify-content-center">
                    @forelse ($products as $prod)
                        <div class="border border-primary rounded position-relative vesitable-item">
                            <a href="{{ route('product.detail', ['slug' => $prod->slug, 'product_name' => Str::slug($prod->product_name), 'id' => $prod->id]) }}"
                                wire:navigate>
                                <div class="vesitable-img">
                                    <img src="{{ asset('storage/' . $prod->image) }}"
                                        class="img-fluid w-100 rounded-top" alt="">
                                </div>
                            </a>
                            <div class="text-white bg-primary px-3 py-1 rounded position-absolute"
                                style="top: 10px; right: 10px; display:{{ isset($prod->sale_price) ? 'block' : 'none' }}">
                                Promo</div>
                            <div class="pad p-4 border border-secondary border-top-0 rounded-bottom fruits-items">
                                <h6>{{ $prod->product_name }}</h6>
                                <div class="d-flex justify-content-center flex-lg-wrap" style="flex-direction: column;">
                                    <p class="fs-c fs-5 fw-bold mb-2">
                                        <span class="text-decoration-line-through text-danger">
                                            {{ isset($prod->sale_price) ? 'Rp. ' . number_format($prod->price) : '' }}
                                        </span>
                                        <span class="{{ isset($prod->sale_price) ? 'text-success' : 'text-dark' }}">

                                            Rp.
                                            {{ isset($prod->sale_price) ? number_format($prod->sale_price) : number_format($prod->price) }}
                                        </span>
                                    </p>
                                    <a href="{{ route('order', ['id' => Str::slug($prod->id)]) }}" target="_blank"
                                        class="order btn border btn-primary rounded-pill px-3 text-white">
                                        <i class="fab fa-whatsapp me-2 text-white"></i>Pesan</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Produk belum tersedia</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- Single Product End -->
</div>
