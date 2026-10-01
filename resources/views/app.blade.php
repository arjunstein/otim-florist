<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>Otim Florist</title>
    <meta name="description" content="Toko bunga terpercaya untuk buket bunga, bunga papan, standing flowers, dan dekorasi bunga segar untuk setiap momen bermakna.">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @php
        $pageProps = $page['props'] ?? [];
        $currentOgImage = $pageProps['ogImage'] ?? asset('images/og-image.jpg');
        $currentOgTitle = $pageProps['ogTitle'] ?? 'Otim Florist — Toko Bunga & Karangan Bunga Terpercaya';
        $currentOgDescription = $pageProps['ogDescription'] ?? 'Toko bunga terpercaya untuk buket bunga, bunga papan, standing flowers, dan dekorasi bunga segar untuk setiap momen bermakna.';
    @endphp

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Otim Florist">
    <meta property="og:title" content="{{ $currentOgTitle }}">
    <meta property="og:description" content="{{ $currentOgDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $currentOgImage }}">
    <meta property="og:image:secure_url" content="{{ $currentOgImage }}">
    @if ($currentOgImage === asset('images/og-image.jpg'))
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">
    @endif

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $currentOgTitle }}">
    <meta name="twitter:description" content="{{ $currentOgDescription }}">
    <meta name="twitter:image" content="{{ $currentOgImage }}">
    <script>
        (() => {
            try {
                const pathname = window.location.pathname;
                const isDashboard = pathname.startsWith('/admin') ||
                                    pathname.startsWith('/dashboard') ||
                                    pathname.startsWith('/settings') ||
                                    pathname === '/categories' ||
                                    pathname === '/products';

                if (isDashboard) {
                    const theme = localStorage.getItem('otim-florist-theme') || 'system';
                    const isDark = theme === 'dark' || (theme === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
                    document.documentElement.dataset.theme = theme;
                    document.documentElement.classList.toggle('dark', isDark);
                } else {
                    document.documentElement.dataset.theme = 'light';
                    document.documentElement.classList.remove('dark');
                }
            } catch {
                document.documentElement.dataset.theme = 'light';
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
