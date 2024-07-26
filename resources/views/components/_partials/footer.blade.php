<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-1">
        <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
            <div class="row g-4">
                <div class="d-flex justify-content-between">
                    <div class="col-lg-6">
                        <a href="#">
                            <h1 class="text-primary mb-0">Otim Florist</h1>
                            <p class="text-secondary mb-0">Toko bunga Jakarta</p>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" target="_blank"
                                href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" target="_blank"
                                href="https://instagram.com/otimfloris7"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-lg-6 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Kenapa harus Otim Florist?</h4>
                    <p class="mb-4" style="text-align: justify">
                        Otim Florist menyediakan berbagai karangan bunga untuk berbagai keperluan, termasuk acara
                        pernikahan, ulang tahun, ucapan terima kasih, peringatan, dan momen-momen spesial lainnya.
                    </p>
                    <a href="{{ route('tentang') }}" wire:navigate
                        class="pad-footer btn border-secondary py-2 px-4 rounded-pill text-primary">Selengkapnya</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Kontak Kami</h4>
                    <p>Alamat: Duri Pulo, Gambir, Jakarta Pusat</p>
                    <p>Email: otimfloristjakarta@gmail.com</p>
                    <p>Phone: 081808881477 | 085959596445</p>
                    <p>Menerima pembayaran transfer via</p>
                    <img src="{{ asset('img/bca.png') }}" class="img-fluid" alt="bca">
                    <img src="{{ asset('img/bri.png') }}" class="img-fluid" alt="bri">
                    <img src="{{ asset('img/mandiri.png') }}" class="img-fluid" alt="mandiri">
                </div>
            </div>
        </div>
    </div>
</div>
