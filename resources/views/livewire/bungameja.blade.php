<div>
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">{{ $title }}</h1>
    </div>
    <!-- Single Page Header End -->

    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Produk populer</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <div class="col-xl-3">
                            <div class="input-group w-100 mx-auto d-flex">
                                <input type="search" class="form-control p-3" placeholder="keywords"
                                    aria-describedby="search-icon-1">
                                <span id="search-icon-1" class="input-group-text p-3"><i
                                        class="fa fa-search"></i></span>
                            </div>
                        </div>
                        <div class="col-6"></div>
                        <div class="col-xl-3">
                            <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                                <label for="fruits">Urutkan:</label>
                                <select id="fruits" name="fruitlist" class="border-0 form-select-sm bg-light me-3"
                                    form="fruitform">
                                    <option value="murah">Termurah</option>
                                    <option value="mahal">Termahal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="row g-4 justify-content-center">
                                @forelse ($products as $prod)
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <a href="{{ route('product.detail', ['slug' => $prod->slug, 'id' => $prod->id]) }}"
                                                    wire:navigate>
                                                    <img src="{{ asset('storage/' . $prod->image) }}"
                                                        class="img-fluid w-100 rounded-top" alt="">
                                                </a>
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px; display:{{ $prod->sale_price ? 'block' : 'none' }}">
                                                {{ $prod->sale_price ? 'Promo' : '' }}
                                            </div>
                                            <div
                                                class="p-4 border border-secondary border-top-0 rounded-bottom fruits-item">
                                                <h6>{{ ucwords($prod->product_name) }} {{ $prod->id }}</h6>
                                                <div class="d-flex justify-content-between flex-lg-wrap"
                                                    style="flex-direction: column;">
                                                    <p class="fs-5 fw-bold mb-2">
                                                        <span class="text-decoration-line-through text-danger">
                                                            {{ isset($prod->sale_price) ? 'Rp.' . number_format($prod->price) : '' }}
                                                        </span>
                                                        <span
                                                            class="{{ isset($prod->sale_price) ? 'text-success' : 'text-dark' }}">
                                                            Rp.
                                                            {{ isset($prod->sale_price) ? number_format($prod->sale_price) : number_format($prod->price) }}
                                                        </span>
                                                    </p>
                                                    <a href="#"
                                                        class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fab fa-whatsapp me-2 text-primary"></i>Pesan
                                                        sekarang</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center mt-5">
                                            <p>Produk kosong</p>
                                        </div>
                                    </div>
                                @endforelse
                                <div class="col-12">
                                    <div class="pagination d-flex justify-content-center mt-5">
                                        {{-- {{ $products->links(data: ['scrollTo' => false]) }} --}}
                                        <a wire:click="load" class="btn btn-primary text-white">Produk lainnya</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fruits Shop End-->
</div>
