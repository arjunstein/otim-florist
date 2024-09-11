<div>
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Tentang kami</h1>
    </div>
    <!-- Single Page Header End -->
    <!-- Contact Start -->
    <div class="container-fluid contact">
        <div class="container py-5">
            <div class="p-3 bg-light rounded">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-12">
                        <div class="text-center mx-auto" style="max-width: 100%;">
                            <h1 class="text-primary">Otim Florist</h1>
                            <p class="mb-4" style="text-align: justify">
                                Otim Florist menyediakan berbagai karangan bunga untuk segala keperluan, termasuk
                                acara pernikahan, ulang tahun, ucapan terima kasih, peringatan, dan momen-momen spesial
                                lainnya.
                            <ul>
                                <li style="text-align: justify">Menawarkan berbagai jenis karangan bunga, termasuk buket
                                    bunga, rangkaian meja,
                                    standing flowers, dan bunga papan.</li>
                                <li style="text-align: justify">Menyediakan bunga segar dengan berbagai pilihan jenis,
                                    warna, dan gaya yang dapat
                                    disesuaikan dengan kebutuhan dan selera pelanggan.</li>
                                <li style="text-align: justify">Pelanggan dapat memesan karangan bunga yang disesuaikan
                                    dengan keinginan, baik dalam
                                    hal jenis bunga, warna, maupun desain.</li>
                                <li style="text-align: justify">Memberikan layanan konsultasi untuk membantu pelanggan
                                    memilih karangan bunga yang
                                    tepat sesuai dengan acara dan anggaran.</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="h-100 rounded">
                            <iframe class="rounded w-100" style="height: 400px"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.7418210775227!2d106.80348287442185!3d-6.16531939382193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f72b0b69c4d7%3A0x3efb9f1a21a104c!2sOtim%20Florist!5e0!3m2!1sid!2sid!4v1721319025173!5m2!1sid!2sid"
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <h3 class="text-primary text-center mt-4">Silahkan subscribe untuk mendapatkan info promo</h3>
                    <div class="col-lg-6">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @elseif (session('oops'))
                            <div class="alert alert-danger">
                                {{ session('oops') }}
                            </div>
                        @endif
                        <form wire:submit="subscriber">
                            @error('fullname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="w-100 form-control border-0 py-3 mb-4"
                                placeholder="Nama lengkap" wire:model="fullname">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="email" class="w-100 form-control border-0 py-3 mb-4"
                                placeholder="Email aktif" wire:model="email">
                            @error('messages')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <textarea class="w-100 form-control border-0 mb-4" rows="5" cols="10" placeholder="Tinggalkan pesan"
                                wire:model="messages"></textarea>
                            <button class="w-100 btn form-control border-secondary py-3 bg-primary text-white "
                                type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
</div>
