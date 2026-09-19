<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="storefront min-h-dvh overflow-x-hidden bg-background text-foreground">
        <a
            href="#main-content"
            class="sr-only fixed left-4 top-4 z-50 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground focus:not-sr-only"
        >
            Skip to content
        </a>
        <header class="sticky top-0 z-20 border-b bg-card/95 backdrop-blur">
            <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex min-h-11 items-center gap-2.5 rounded-xl pr-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <svg class="size-8 text-primary" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".14" />
                        <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                        <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                    </svg>
                    <span class="font-semibold tracking-tight">Otim Florist</span>
                </Link>
            </div>
        </header>

        <main id="main-content">
            <slot />
        </main>

        <footer class="border-t bg-card">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-8 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>Thoughtful flowers, simply arranged.</p>
                <p>© {{ new Date().getFullYear() }} Otim Florist</p>
            </div>
        </footer>
    </div>
</template>
