<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>Otim Florist</title>
    <meta name="description" content="Toko bunga terpercaya untuk buket bunga, bunga papan, standing flowers, dan dekorasi bunga segar untuk setiap momen bermakna.">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Otim Florist">
    <meta property="og:title" content="Otim Florist — Toko Bunga &amp; Karangan Bunga Terpercaya">
    <meta property="og:description" content="Toko bunga terpercaya untuk buket bunga, bunga papan, standing flowers, dan dekorasi bunga segar untuk setiap momen bermakna.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:image:secure_url" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Otim Florist — Toko Bunga &amp; Karangan Bunga Terpercaya">
    <meta name="twitter:description" content="Toko bunga terpercaya untuk buket bunga, bunga papan, standing flowers, dan dekorasi bunga segar untuk setiap momen bermakna.">
    <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">
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
