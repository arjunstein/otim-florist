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
                                @forelse ($products as $product)
                                    <div class="col-6 col-md-6 col-lg-4 col-xl-3" wire:key="{{ $product->id }}">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <a href="{{ route('product.detail', ['slug' => $product->slug, 'product_name' => Str::slug($product->product_name), 'id' => $product->id]) }}"
                                                    wire:navigate>
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        class="img-fluid w-100 rounded-top" alt="">
                                                </a>
                                            </div>
                                            <div class="pad-1 promo text-white bg-primary px-3 py-1 rounded position-absolute"
                                                style="top: 5px; left: 5px; text-align: center; display:{{ isset($product->sale_price) ? 'block' : 'none' }}">
                                                Promo</div>
                                            <div
                                                class="pad p-4 border border-secondary border-top-0 rounded-bottom fruits-item">
                                                <h6>{{ strtoupper($product->product_name) }}</h6>
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
                                                    <a href="{{ route('order', ['id' => Str::slug($product->id)]) }}"
                                                        target="_blank"
                                                        class="order btn border btn-primary rounded-pill px-3 text-white">
                                                        <i class="fab fa-whatsapp me-2 text-white"></i>Pesan</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center mt-5">
                                            <p>Produk belum tersedia</p>
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
