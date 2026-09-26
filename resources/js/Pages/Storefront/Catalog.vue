<script setup lang="ts">
import ProductCard from '@/Components/Storefront/ProductCard.vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import type { StoreInfo } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({ layout: StorefrontLayout });

const props = defineProps<{
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

const page = usePage<{ store?: StoreInfo }>();
const store = computed<StoreInfo>(() => page.props.store ?? {
    name: 'Otim Florist',
    phone: '',
    address: 'Jl. Mawar No. 12, Jakarta',
    hours: '08:00–20:00 daily',
    google_reviews_url: 'https://share.google/v4xDWRoD4ANg5Ft3K',
    google_rating: 5.0,
    google_reviews_count: 27,
});

const googleRating = computed(() => {
    const val = Number(store.value.google_rating);
    return isNaN(val) || val <= 0 ? '5.0' : val.toFixed(1);
});

const googleReviewsCount = computed(() => {
    const val = Number(store.value.google_reviews_count);
    return isNaN(val) ? 27 : val;
});

const googleReviewsUrl = computed(() => {
    return store.value.google_reviews_url || 'https://share.google/v4xDWRoD4ANg5Ft3K';
});

const selectedCategory = ref<string | null>(null);

const availableCategories = computed(() => {
    const map = new Map<string, string>();
    props.products.forEach((product) => {
        map.set(product.category.slug, product.category.name);
    });
    return Array.from(map.entries()).map(([slug, name]) => ({ slug, name }));
});

const displayedProducts = computed(() => {
    if (!selectedCategory.value) {
        return props.products;
    }
    return props.products.filter((product) => product.category.slug === selectedCategory.value);
});
</script>

<template>
    <Head :title="`Rangkaian bunga untuk setiap momen | ${store.name}`">
        <meta name="description" :content="`Jelajahi rangkaian bunga pilihan dari ${store.name}.`" />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" :content="`Rangkaian bunga untuk setiap momen | ${store.name}`" />
        <meta property="og:description" :content="`Jelajahi rangkaian bunga pilihan dari ${store.name}.`" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonicalUrl" />
    </Head>

    <section class="relative isolate overflow-hidden border-b border-primary/20 bg-primary text-primary-foreground">
        <div class="absolute -right-24 -top-32 size-96 rounded-full bg-accent/25 blur-3xl" aria-hidden="true" />
        <div class="absolute -bottom-36 -left-20 size-96 rounded-full bg-primary-foreground/10 blur-3xl" aria-hidden="true" />
        <div class="absolute right-1/4 top-1/2 size-72 rounded-full border border-primary-foreground/10" aria-hidden="true" />

        <div class="relative mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-[1.15fr_0.85fr] lg:items-center lg:px-8 lg:py-28">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-primary-foreground/20 bg-primary-foreground/10 px-3.5 py-1 text-xs font-semibold tracking-wide text-primary-foreground/90 backdrop-blur-xs">
                    <span class="size-2 rounded-full bg-accent animate-pulse" />
                    <span>Floral Studio & Boutique</span>
                </div>

                <h1 class="mt-6 text-4xl leading-[1.08] font-medium tracking-tight sm:text-5xl lg:text-6xl">
                    Bunga segar untuk setiap momen bermakna.
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-primary-foreground/80 sm:text-lg">
                    Rangkaian bunga pilihan untuk perayaan, ungkapan kasih, dan setiap detik berharga yang layak dikenang selamanya.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a
                        href="#collection"
                        class="inline-flex min-h-12 items-center justify-center rounded-xl bg-primary-foreground px-6 text-sm font-semibold text-primary shadow-sm transition-all duration-200 hover:bg-primary-foreground/95 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-foreground focus-visible:ring-offset-2 focus-visible:ring-offset-primary"
                    >
                        Lihat Koleksi Bunga
                    </a>
                    <a
                        v-if="store.phone"
                        :href="`https://wa.me/${store.phone}?text=${encodeURIComponent('Halo ' + store.name + ', saya ingin bertanya mengenai pemesanan bunga.')}`"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-primary-foreground/25 bg-primary-foreground/10 px-5 text-sm font-semibold text-primary-foreground backdrop-blur-xs transition-colors duration-200 hover:bg-primary-foreground/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-foreground"
                    >
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span>Konsultasi Florist</span>
                    </a>
                </div>

                <div class="mt-10 flex flex-wrap items-center gap-6 border-t border-primary-foreground/15 pt-6 text-xs text-primary-foreground/75">
                    <div class="flex items-center gap-2">
                        <svg class="size-4 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        <span>100% Bunga Segar Pilihan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-4 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        <span>Gratis Kartu Ucapan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-4 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        <span>Pengiriman Terlindungi</span>
                    </div>
                </div>
            </div>

            <div class="relative mx-auto flex w-full max-w-sm flex-col items-center">
                <div class="relative grid aspect-square w-full place-items-center rounded-[2.5rem] border border-primary-foreground/20 bg-gradient-to-b from-primary-foreground/15 to-primary-foreground/5 p-8 shadow-2xl backdrop-blur-md">
                    <svg class="size-48 text-primary-foreground/90 transition-transform duration-700 hover:rotate-6" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".2" />
                        <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                        <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".5" />
                    </svg>

                    <a
                        :href="googleReviewsUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group absolute -bottom-5 flex items-center gap-2 rounded-full border border-border/80 bg-background/95 px-4 py-2 text-xs font-semibold text-foreground shadow-lg backdrop-blur-sm transition-all hover:scale-105 hover:border-primary/50 hover:shadow-xl"
                        :title="`Lihat ulasan ${store.name} di Google Maps`"
                    >
                        <svg class="size-3.5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                        </svg>
                        <span class="font-bold text-amber-500">★ {{ googleRating }}</span>
                        <span class="text-muted-foreground transition-colors group-hover:text-foreground">
                            ({{ googleReviewsCount }} ulasan di Google)
                        </span>
                        <svg class="size-3 text-muted-foreground/60 transition-transform group-hover:translate-x-0.5 group-hover:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="collection" class="border-t border-border/70 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-primary">Katalog Eksklusif</p>
                    <h2 class="mt-2 text-3xl font-semibold sm:text-4xl">Koleksi Bunga Pilihan</h2>
                </div>

                <div v-if="availableCategories.length > 1" class="flex flex-wrap items-center gap-1.5 pt-2">
                    <button
                        type="button"
                        :class="[
                            'rounded-full px-3.5 py-1.5 text-xs font-semibold transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring',
                            selectedCategory === null
                                ? 'bg-primary text-primary-foreground shadow-xs'
                                : 'bg-secondary/70 text-muted-foreground hover:bg-secondary hover:text-foreground',
                        ]"
                        @click="selectedCategory = null"
                    >
                        Semua Koleksi
                    </button>
                    <button
                        v-for="cat in availableCategories"
                        :key="cat.slug"
                        type="button"
                        :class="[
                            'rounded-full px-3.5 py-1.5 text-xs font-semibold transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring',
                            selectedCategory === cat.slug
                                ? 'bg-primary text-primary-foreground shadow-xs'
                                : 'bg-secondary/70 text-muted-foreground hover:bg-secondary hover:text-foreground',
                        ]"
                        @click="selectedCategory = cat.slug"
                    >
                        {{ cat.name }}
                    </button>
                </div>
            </div>

            <div v-if="displayedProducts.length" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <ProductCard v-for="product in displayedProducts" :key="product.slug" :product="product" />
            </div>

            <div v-else class="mt-10 rounded-3xl border border-dashed border-border bg-card p-12 text-center">
                <div class="mx-auto grid size-12 place-items-center rounded-2xl bg-secondary text-primary">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22v-7M9 7l3-4 3 4M6 13l6-3 6 3" />
                    </svg>
                </div>
                <p class="mt-4 text-base font-semibold">Koleksi kami segera hadir.</p>
                <p class="mt-1.5 text-sm text-muted-foreground">Silakan kembali lagi untuk melihat rangkaian bunga segar terbaru.</p>
            </div>
        </div>
    </section>

    <section aria-label="Keunggulan Layanan" class="border-t border-border/70 bg-secondary/30 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary">Standar Kualitas</p>
                <h2 class="mt-2 text-3xl font-semibold sm:text-4xl">Mengapa Memilih {{ store.name }}?</h2>
                <p class="mt-3 text-sm text-muted-foreground max-w-md mx-auto">Kami mendedikasikan ketelitian dan cinta pada setiap tangkai bunga yang Anda pesan.</p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-3">
                <div class="flex flex-col items-center rounded-3xl border border-border/70 bg-card p-7 text-center shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <path d="m4.93 4.93 4.24 4.24M14.83 9.17l4.24-4.24M14.83 14.83l4.24 4.24M9.17 14.83l-4.24 4.24" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-foreground">Sentuhan Florist Ahli</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                        Ditata secara artistik dengan komposisi warna harmonis yang memancarkan kehangatan dan kemewahan.
                    </p>
                </div>

                <div class="flex flex-col items-center rounded-3xl border border-border/70 bg-card p-7 text-center shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-foreground">Pengiriman Terjaga</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                        Dilengkapi wadah cadangan air (*water tube*) agar buket tetap segar dan tegak hingga sampai ke tangan penerima.
                    </p>
                </div>

                <div class="flex flex-col items-center rounded-3xl border border-border/70 bg-card p-7 text-center shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-foreground">Free Ongkir Jakbar & Jakpus</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                        Gratis ongkos kirim khusus area Jakarta Barat dan Jakarta Pusat dengan pengiriman aman dan tepat waktu.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

