<!-- Spinner Start -->
<div id="spinner"
    class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<!-- Spinner End -->

<div class="container-fluid fixed-top">
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#"
                        class="text-white">Jakarta Pusat</a></small>
                <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#"
                        class="text-white">otimfloristjakarta@gmail.com</a></small>
            </div>
        </div>
    </div>
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="/" wire:navigate class="navbar-brand">
                <h1 class="text-primary display-6">Otim Florist</h1>
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="/" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}"
                        wire:navigate>Home</a>
                    <a href="{{ route('bungapapan') }}"
                        class="nav-item nav-link {{ Request::routeIs('bungapapan') ? 'active' : '' }}"
                        wire:navigate>Bunga papan</a>
                    <a href="{{ route('bungastanding') }}"
                        class="nav-item nav-link {{ Request::routeIs('bungastanding') ? 'active' : '' }}"
                        wire:navigate>Bunga standing</a>
                    <a href="{{ route('buket') }}"
                        class="nav-item nav-link {{ Request::routeIs('buket') ? 'active' : '' }}" wire:navigate>Hand
                        bouquet</a>
                    <a href="{{ route('bungameja') }}"
                        class="nav-item nav-link {{ Request::routeIs('bungameja') ? 'active' : '' }}"
                        wire:navigate>Bunga
                        meja</a>
                    <a href="{{ route('bungasalib') }}"
                        class="nav-item nav-link {{ Request::routeIs('bungasalib') ? 'active' : '' }}"
                        wire:navigate>Bunga salib</a>
                    <a href="{{ route('promo') }}"
                        class="nav-item nav-link {{ Request::routeIs('promo') ? 'active' : '' }}"
                        wire:navigate>Promo</a>
                    <a href="{{ route('tentang') }}"
                        class="nav-item nav-link {{ Request::routeIs('tentang') ? 'active' : '' }}"
                        wire:navigate>Tentang
                        kami</a>
                </div>
                <div class="d-flex m-3 me-0">
                    <button
                        class="btn-search btn border border-secondary-nav btn-md-square rounded-circle bg-white me-4"
                        data-bs-toggle="modal" data-bs-target="#searchModal"><i
                            class="fas fa-search text-primary"></i></button>
                </div>
            </div>
        </nav>
    </div>
</div>

<!-- Modal Search Start -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="input-group w-75 mx-auto d-flex">
                    <input type="search" class="form-control p-3" placeholder="keywords"
                        aria-describedby="search-icon-1">
                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Search End -->
