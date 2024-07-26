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
                            <h4 class="fw-bold mb-3">{{ ucfirst($produk->product_name) . ' ' . $produk->id }}</h4>
                            <p class="mb-3">Kategori: {{ ucfirst($produk->category->category_name) }}</p>
                            <h5 class="fw-bold text-danger mb-3 text-decoration-line-through">
                                {{ isset($produk->sale_price) ? 'Rp. ' . number_format($produk->price) : '' }}</h5>
                            <h5 class="fw-bold text-success mb-3">Rp.
                                {{ isset($produk->sale_price) ? number_format($produk->sale_price) : number_format($produk->price) }}
                            </h5>
                            <p class="mb-4">{{ $produk->product_description }}</p>
                            <div class="input-group quantity mb-5" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center border-0"
                                    value="1">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <a href="#"
                                class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i
                                    class="fa fa-shopping-bag me-2 text-primary"></i> Beli</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="row g-4 fruite">
                        <div class="col-lg-12">
                            <div class="mb-4">
                                <h4>Kategori produk</h4>
                                <ul class="list-unstyled fruite-categorie">
                                    @foreach ($category as $ctg)
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="#"><i
                                                        class="fas fa-store me-2"></i>{{ $ctg->category_name }}</a>
                                                <span>{{ $ctg->product->count() }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="position-relative">
                                <img src="{{ asset('img/banner-fruits.jpg') }}" class="img-fluid w-100 rounded"
                                    alt="">
                                <div class="position-absolute"
                                    style="top: 50%; right: 10px; transform: translateY(-50%);">
                                    <h3 class="text-secondary fw-bold">Otim <br> Florist <br> Banner</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="fw-bold mb-0">Produk lainnya</h1>
            <div class="vesitable">
                <div class="owl-carousel vegetable-carousel justify-content-center">
                    @foreach ($produks as $prod)
                        <div class="border border-primary rounded position-relative vesitable-item">
                            <a href="{{ route('product.detail', ['slug' => $prod->slug, 'id' => $prod->id]) }}"
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
                                <h6>{{ ucfirst($prod->product_name) . ' ' . $prod->id }}</h6>
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
                                    <a href="#"
                                        class="btn border border-secondary rounded-pill px-3 text-primary">
                                        <i class="fab fa-whatsapp me-2 text-primary"></i>Pesan</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Single Product End -->
</div>
