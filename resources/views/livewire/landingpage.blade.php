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
                                        class="img-fluid w-100 h-100 bg-secondary rounded" alt="{{ $ads->title }}">
                                    @if ($ads->url)
                                        <a href="{{ url($ads->url) }}" class="w-100 h-100" target="_blank"></a>
                                    @else
                                        <a href="{{ route('promo') }}" class="w-100 h-100" wire:navigate></a>
                                    @endif
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
                                <div class="col-6 col-md-6 col-lg-4 col-xl-3" wire:key="{{ $product->id }}">
                                    <div class="rounded position-relative fruite-item">
                                        <div class="fruite-img">
                                            <a href="{{ route('product.detail', ['slug' => $product->slug, 'product_name' => Str::slug($product->product_name), 'id' => $product->id]) }}"
                                                wire:navigate>
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid w-100 rounded-top"
                                                    alt="{{ $product->category->category_name . $product->id }}">
                                            </a>
                                        </div>
                                        <div class="pad-1 promo text-white bg-primary px-3 py-1 rounded position-absolute"
                                            style="top: 5px; left: 5px; display:{{ isset($product->sale_price) ? 'block' : 'none' }}">
                                            Promo</div>
                                        <div
                                            class="pad p-4 border border-secondary border-top-0 rounded-bottom fruits-item">
                                            <h6>{{ $product->product_name }}</h6>
                                            <div class="d-flex justify-content-center flex-lg-wrap"
                                                style="flex-direction: column;">
                                                <p class="fs-c fs-5 fw-bold mb-2">
                                                    <span class="text-decoration-line-through text-danger">
                                                        {{ isset($product->discount) ? 'Rp. ' . number_format($product->price) : '' }}
                                                    </span>
                                                    <span
                                                        class="{{ isset($product->discount) ? 'text-success' : 'text-dark' }}">

                                                        Rp.
                                                        {{ isset($product->discount) ? number_format($product->sale_price) : number_format($product->price) }}
                                                    </span>
                                                </p>
                                                <a href="{{ route('order', ['id' => Str::slug($product->id)]) }}"
                                                    target="_blank"
                                                    class="order btn border btn-primary rounded-pill px-3 text-white">
                                                    <i class="fab fa-whatsapp me-2 text-white"></i>Pesan</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>Produk kosong</p>
                            @endforelse
                            <div class="pagination justify-content-center mt-5">
                                {{-- {!! $products->links(data: ['scrollTo' => false]) !!} --}}
                                <a wire:click="load" class="btn btn-primary text-white">Produk lainnya</a>
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
                            <p class="mb-0">Untuk area Jakarta Pusat dan Jakarta Barat</p>
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
                            <p class="mb-0">100% amanah pembayaran via transfer</p>
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
                            <p class="mb-0">Kualitas terjamin pengalaman lebih dari 10 tahun</p>
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
                            <p class="mb-0">Siap melayani anda dengan komitmen yang kami punya</p>
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
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative" style="height: 300px">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">
                                terimakash atas bantuannyaa otim florist pelayanan cepat proses cepat kwalitas oke...
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="{{ asset('img/supra.png') }}" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">Sisca Setya Evi</h4>
                                <p class="m-0 pb-3">PT. Suprajaya Duaribu Satu</p>
                            </div>
                        </div>
                        <p class="pt-3">
                            <a href="https://g.page/r/CUwQGqLxue8DEAE/review"><i class="fab fa-google"></i> Lihat
                                review google</a>
                        </p>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative" style="height: 300px">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">
                                terimakash atas bantuannyaa otim florist pelayanan cepat proses cepat kwalitas oke...
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="{{ asset('img/bpr-logo.png') }}" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">andes jampang channel</h4>
                                <p class="m-0 pb-3">PT. BPR Mitra Daya Mandiri</p>
                            </div>
                        </div>
                        <p class="pt-3">
                            <a href="https://g.page/r/CUwQGqLxue8DEAE/review"><i class="fab fa-google"></i> Lihat
                                review google</a>
                        </p>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative" style="height: 300px">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">
                                Bagus dan Pelayanan cepat Owner ramah dan baik Selalu berlangganan disini Terimakasih
                                💕🙏🏻
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="{{ asset('img/bbksda-sumut.png') }}" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">Putri Sagala</h4>
                                <p class="m-0 pb-3">BBKSDA Sumatera Utara</p>
                            </div>
                        </div>
                        <p class="pt-3">
                            <a href="https://g.page/r/CUwQGqLxue8DEAE/review"><i class="fab fa-google"></i> Lihat
                                review google</a>
                        </p>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative" style="height: 300px">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">
                                Fast response dan memberikan layanan yg terbaik untuk setiap customer 🙏🙏
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="{{ asset('img/datascript.png') }}" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">Maria Adriana</h4>
                                <p class="m-0 pb-3">Datascript Business Solution</p>
                            </div>
                        </div>
                        <p class="pt-3">
                            <a href="https://g.page/r/CUwQGqLxue8DEAE/review"><i class="fab fa-google"></i> Lihat
                                review google</a>
                        </p>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative" style="height: 300px">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">
                                Udah langganan di toko ini, Karna penjual sangat ramah, bunga masih seger, hiasannya
                                sangat rapih, amanah
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="{{ asset('img/jalaprima.png') }}" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">Alex Waskito</h4>
                                <p class="m-0 pb-3">PT. Jalaprima Perkasa</p>
                            </div>
                        </div>
                        <p class="pt-3">
                            <a href="https://g.page/r/CUwQGqLxue8DEAE/review"><i class="fab fa-google"></i> Lihat
                                review google</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
