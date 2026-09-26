<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>Otim Florist</title>
    <script>
        (() => {
            try {
                const pathname = window.location.pathname;
                const isDashboard = pathname.startsWith('/dashboard') ||
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
