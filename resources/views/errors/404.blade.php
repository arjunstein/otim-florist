<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Halaman Tidak Ditemukan | Otim Florist</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-background text-foreground min-h-dvh flex flex-col items-center justify-center p-6 antialiased font-sans">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="mx-auto flex size-20 items-center justify-center rounded-2xl bg-primary/10 text-primary">
            <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        </div>
        <div class="space-y-2">
            <p class="text-sm font-semibold tracking-wider uppercase text-primary">Error 404</p>
            <h1 class="text-3xl font-semibold tracking-tight text-foreground sm:text-4xl">Halaman Tidak Ditemukan</h1>
            <p class="text-sm text-muted-foreground leading-relaxed">
                Halaman yang Anda tuju tidak dapat ditemukan atau alamat URL tidak sesuai.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            @auth
                <a href="{{ route('dashboard.overview') }}" class="w-full sm:w-auto inline-flex min-h-11 items-center justify-center rounded-xl bg-primary px-6 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-90">
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('storefront.home') }}" class="w-full sm:w-auto inline-flex min-h-11 items-center justify-center rounded-xl bg-primary px-6 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-90">
                    Kembali ke Beranda
                </a>
            @endauth
            <a href="{{ route('storefront.home') }}" class="w-full sm:w-auto inline-flex min-h-11 items-center justify-center rounded-xl border border-border bg-card px-5 text-sm font-semibold text-foreground transition hover:bg-secondary">
                Lihat Katalog Toko
            </a>
        </div>
    </div>
</body>
</html>
