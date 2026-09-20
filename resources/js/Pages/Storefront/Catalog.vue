<script setup lang="ts">
import ProductCard from '@/Components/Storefront/ProductCard.vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({ layout: StorefrontLayout });

defineProps<{
    canonicalUrl: string;
    products: Array<{
        name: string;
        slug: string;
        imageUrl: string | null;
        price: number;
        salePrice: number | null;
        category: {
            name: string;
            slug: string;
        };
    }>;
}>();
</script>

<template>
    <Head title="Rangkaian bunga untuk setiap momen | Otim Florist">
        <meta name="description" content="Jelajahi rangkaian bunga pilihan dari Otim Florist." />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" content="Rangkaian bunga untuk setiap momen | Otim Florist" />
        <meta property="og:description" content="Jelajahi rangkaian bunga pilihan dari Otim Florist." />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonicalUrl" />
    </Head>

    <section class="relative isolate overflow-hidden border-b bg-primary text-primary-foreground">
        <div class="absolute -right-20 -top-24 size-80 rounded-full bg-accent/35 blur-3xl" aria-hidden="true" />
        <div class="absolute -bottom-32 left-1/3 size-96 rounded-full border border-primary-foreground/10" aria-hidden="true" />
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1.1fr_.9fr] lg:items-center lg:px-8 lg:py-28">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-primary-foreground/65">Otim Florist</p>
                <h1 class="mt-5 text-5xl leading-[0.94] sm:text-6xl lg:text-7xl">Bunga untuk setiap momen bermakna.</h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-primary-foreground/70 sm:text-lg">Rangkaian bunga pilihan untuk perayaan, ungkapan terima kasih, dan setiap momen yang layak dikenang.</p>
                <a href="#collection" class="mt-9 inline-flex min-h-11 items-center rounded-xl bg-primary-foreground px-5 text-sm font-semibold text-primary shadow-sm transition-colors duration-200 hover:bg-primary-foreground/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-foreground focus-visible:ring-offset-2 focus-visible:ring-offset-primary">
                    Lihat koleksi
                </a>
            </div>
            <div class="relative mx-auto grid aspect-square w-full max-w-sm place-items-center rounded-[2rem] border border-primary-foreground/15 bg-primary-foreground/10 shadow-2xl backdrop-blur-sm">
                <svg class="size-52 text-primary-foreground" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".14" />
                    <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                    <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                </svg>
            </div>
        </div>
    </section>

    <section id="collection" class="border-t border-primary/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Koleksi pilihan</p>
                <h2 class="mt-3 text-4xl leading-none sm:text-5xl">Pilihan segar untuk Anda</h2>
            </div>
            <div v-if="products.length" class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <ProductCard v-for="product in products" :key="product.slug" :product="product" />
            </div>
            <div v-else class="mt-7 rounded-2xl border border-dashed bg-card p-8 text-center">
                <p class="font-semibold">Koleksi kami segera hadir.</p>
                <p class="mt-2 text-sm text-muted-foreground">Silakan kembali lagi untuk melihat rangkaian bunga terbaru.</p>
            </div>
        </div>
    </section>
</template>
