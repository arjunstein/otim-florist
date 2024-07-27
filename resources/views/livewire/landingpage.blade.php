<div>
    <div class="container-fluid hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <h4 class="mb-3 text-secondary">100% Berkualitas</h4>
                    <h1 class="mb-5 display-3 text-primary">Terbuat dari bunga pilihan dan fresh</h1>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            @forelse ($ad as $i => $ads)
                                <div class="carousel-item {{ $i == 0 ? 'active' : '' }} rounded">
                                    <img src="{{ asset('storage/' . $ads->image) }}"
                                        class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                                    <a href="#"
                                        class="btn px-4 py-2 text-white rounded">{{ ucwords($ads->title) }}</a>
                                </div>
                            @empty
                            @endforelse
                        </div>

                        @if ($ad->isNotEmpty() && $ad->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselId"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselId"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>Produk populer</h1>
                    </div>
                </div>
                <div class="row g-4 py-5">
                    <div class="col-lg-12">
                        <div class="row g-4 justify-content-center">
                            @forelse ($products as $product)
                                <div class="col-6 col-md-6 col-lg-4 col-xl-3">
                                    <div class="rounded position-relative fruite-item">
                                        <div class="fruite-img">
                                            <a href="{{ route('product.detail', ['slug' => $product->slug, 'id' => $product->id]) }}"
                                                wire:navigate>
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid w-100 rounded-top" alt="">
                                            </a>
                                        </div>
                                        <div class="pad-1 promo text-white bg-primary px-3 py-1 rounded position-absolute"
                                            style="top: 5px; left: 5px; display:{{ isset($product->sale_price) ? 'block' : 'none' }}">Promo</div>
                                        <div
                                            class="pad p-4 border border-secondary border-top-0 rounded-bottom fruits-item">
                                            <h6>{{ ucfirst($product->product_name) . ' ' . $product->id }}</h6>
                                            <div class="d-flex justify-content-center flex-lg-wrap"
                                                style="flex-direction: column;">
                                                <p class="fs-c fs-5 fw-bold mb-2">
                                                    <span class="text-decoration-line-through text-danger">
                                                        {{ isset($product->sale_price) ? 'Rp. ' . number_format($product->price) : '' }}
                                                    </span>
                                                    <span
                                                        class="{{ isset($product->sale_price) ? 'text-success' : 'text-dark' }}">

                                                        Rp.
                                                        {{ isset($product->sale_price) ? number_format($product->sale_price) : number_format($product->price) }}
                                                    </span>
                                                </p>
                                                <a href="#"
                                                    class="btn border border-secondary rounded-pill px-3 text-primary">
                                                    <i class="fab fa-whatsapp me-2 text-primary"></i>Pesan</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>Produk kosong</p>
                            @endforelse
                            <div class="pagination justify-content-center mt-5">
                                {{ $products->links(data: ['scrollTo' => false]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featurs Section Start -->
    <div class="container-fluid featurs">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-car-side fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Free Ongkir</h5>
                            <p class="mb-0">Untuk area Jakarta dan sekitarnya</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-user-shield fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Pembayaran aman</h5>
                            <p class="mb-0">100% security payment</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-exchange-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Profesional</h5>
                            <p class="mb-0">Kualitas terjamin</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fa fa-phone-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>24/7 Fast Response</h5>
                            <p class="mb-0">Siap melayani anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Featurs Section End -->

    <!-- Tastimonial Start -->
    <div class="container-fluid testimonial">
        <div class="container py-5">
            <div class="testimonial-header text-center">
                <h4 class="text-primary">Testimoni</h4>
                <h1 class="display-5 mb-5 text-dark">Kata pelanggan</h1>
            </div>
            <div class="owl-carousel testimonial-carousel">
                @foreach ($testimoni as $testi)
                    <div class="testimonial-item img-border-radius bg-light rounded p-4">
                        <div class="position-relative" style="height: 300px">
                            <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                                style="bottom: 30px; right: 0;"></i>
                            <div class="mb-4 pb-4 border-bottom border-secondary">
                                <p class="mb-0">
                                    {{ $testi->testimonial_sentence }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center flex-nowrap">
                                <div class="d-block">
                                    <h4 class="text-dark">{{ $testi->client_name }}</h4>
                                    <p class="m-0 pb-3">{{ $testi->client_profession }}</p>
                                </div>
                            </div>
                            <p class="pt-3">
                                <a href="https://g.page/r/CUwQGqLxue8DEAE/review"><i class="fab fa-google"></i> Lihat
                                    review google</a>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
