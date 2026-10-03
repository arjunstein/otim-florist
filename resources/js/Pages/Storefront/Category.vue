<script setup lang="ts">
import ProductCard from '@/Components/Storefront/ProductCard.vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import SeoJsonLd from '@/Components/SeoJsonLd.vue';
import type { StoreInfo } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({ layout: StorefrontLayout });

const INITIAL_BATCH_SIZE = 12;
const BATCH_INCREMENT = 12;

const props = defineProps<{
    canonicalUrl: string;
    category: {
        name: string;
        productCount: number;
    };
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

const page = usePage<{ store?: StoreInfo; defaultOgImage?: string }>();
const defaultOgImage = computed(() => page.props.defaultOgImage || '/images/og-image.jpg');
const store = computed<StoreInfo>(() => page.props.store ?? {
    name: 'Otim Florist',
    phone: '',
    address: 'Jl. Mawar No. 12, Jakarta',
    hours: '08:00–20:00 daily',
});

const origin = computed(() => {
    try {
        return new URL(props.canonicalUrl).origin;
    } catch {
        return '';
    }
});

const breadcrumbSchema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: [
        {
            '@type': 'ListItem',
            position: 1,
            name: 'Beranda',
            item: origin.value || '/',
        },
        {
            '@type': 'ListItem',
            position: 2,
            name: props.category.name,
            item: props.canonicalUrl,
        },
    ],
}).replace(/</g, '\\u003c'));

const visibleCount = ref(INITIAL_BATCH_SIZE);

const displayedProducts = computed(() => {
    return props.products.slice(0, visibleCount.value);
});

const hasMoreProducts = computed(() => {
    return visibleCount.value < props.products.length;
});

const remainingCount = computed(() => {
    return Math.max(0, props.products.length - visibleCount.value);
});

function loadMore(): void {
    visibleCount.value += BATCH_INCREMENT;
}
</script>

<template>
    <Head :title="`${category.name} Jakarta — Beli Online | ${store.name}`">
        <meta name="description" :content="`Beli ${category.name.toLowerCase()} Jakarta di ${store.name}. Rangkaian segar untuk pernikahan, wisuda & perayaan. Gratis ongkir Jakbar & Jakpus. Pesan via WhatsApp.`" />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" :content="`${category.name} Jakarta — Beli Online | ${store.name}`" />
        <meta property="og:description" :content="`Beli ${category.name.toLowerCase()} Jakarta di ${store.name}. Rangkaian segar untuk pernikahan, wisuda & perayaan.`" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" :content="defaultOgImage" />
        <meta property="og:image:secure_url" :content="defaultOgImage" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:type" content="image/jpeg" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="`${category.name} Jakarta — Beli Online | ${store.name}`" />
        <meta name="twitter:description" :content="`Beli ${category.name.toLowerCase()} Jakarta di ${store.name}. Rangkaian segar untuk pernikahan, wisuda & perayaan.`" />
        <meta name="twitter:image" :content="defaultOgImage" />
    </Head>

    <SeoJsonLd :content="breadcrumbSchema" />

    <section class="border-b border-border/80 bg-gradient-to-b from-secondary/50 to-secondary/20">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
            <nav aria-label="Jejak navigasi">
                <ol class="flex min-h-11 items-center gap-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                    <li>
                        <Link href="/" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            Beranda
                        </Link>
                    </li>
                    <li aria-hidden="true" class="text-border">/</li>
                    <li aria-current="page" class="text-foreground font-bold">{{ category.name }}</li>
                </ol>
            </nav>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-baseline sm:justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">
                        Koleksi Pilihan
                    </span>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">
                        {{ category.name }}
                    </h1>
                </div>

                <div class="inline-flex items-center gap-2 rounded-full border border-border bg-card px-3.5 py-1.5 text-xs font-semibold text-muted-foreground shadow-2xs">
                    <span class="size-2 rounded-full bg-primary" />
                    <span>{{ category.productCount }} pilihan rangkaian</span>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div v-if="displayedProducts.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <ProductCard v-for="product in displayedProducts" :key="product.slug" :product="product" />
        </div>

        <div v-if="hasMoreProducts" class="mt-12 flex flex-col items-center gap-3 text-center">
            <p class="text-xs font-medium text-muted-foreground">
                Menampilkan {{ displayedProducts.length }} dari {{ products.length }} rangkaian bunga
            </p>
            <div class="h-1.5 w-48 overflow-hidden rounded-full bg-secondary">
                <div
                    class="h-full rounded-full bg-primary transition-all duration-300"
                    :style="{ width: `${(displayedProducts.length / products.length) * 100}%` }"
                />
            </div>
            <button
                type="button"
                class="group inline-flex min-h-11 items-center gap-2 rounded-2xl bg-secondary px-6 py-2.5 text-sm font-semibold text-foreground shadow-2xs transition-all duration-200 hover:bg-primary hover:text-primary-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                @click="loadMore"
            >
                <span>Muat Lebih Banyak</span>
                <span class="rounded-full bg-foreground/10 px-2 py-0.5 text-xs group-hover:bg-primary-foreground/20">
                    +{{ Math.min(BATCH_INCREMENT, remainingCount) }}
                </span>
                <svg class="size-4 transition-transform duration-200 group-hover:translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
        </div>

        <div v-else-if="!products.length" class="rounded-3xl border border-dashed border-border bg-card p-12 text-center">
            <p class="font-semibold text-foreground">Belum ada produk dalam koleksi ini.</p>
            <p class="mt-1.5 text-sm text-muted-foreground">Florist kami sedang menyiapkan rangkaian bunga baru.</p>
            <Link href="/" class="mt-6 inline-flex min-h-11 items-center rounded-xl bg-primary px-5 text-sm font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                Lihat semua koleksi bunga
            </Link>
        </div>
    </section>
</template>

