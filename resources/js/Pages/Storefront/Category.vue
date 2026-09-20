<script setup lang="ts">
import ProductCard from '@/Components/Storefront/ProductCard.vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import type { StoreInfo } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: StorefrontLayout });

defineProps<{
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

const page = usePage<{ store?: StoreInfo }>();
const store = computed<StoreInfo>(() => page.props.store ?? {
    name: 'Otim Florist',
    phone: '',
    address: 'Jl. Mawar No. 12, Jakarta',
    hours: '08:00–20:00 daily',
});
</script>

<template>
    <Head :title="`Koleksi ${category.name} | ${store.name}`">
        <meta name="description" :content="`Jelajahi koleksi ${category.name.toLowerCase()} pilihan dari ${store.name}.`" />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" :content="`Koleksi ${category.name} | ${store.name}`" />
        <meta property="og:description" :content="`Jelajahi koleksi ${category.name.toLowerCase()} pilihan dari ${store.name}.`" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonicalUrl" />
    </Head>

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
        <div v-if="products.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <ProductCard v-for="product in products" :key="product.slug" :product="product" />
        </div>
        <div v-else class="rounded-3xl border border-dashed border-border bg-card p-12 text-center">
            <p class="font-semibold text-foreground">Belum ada produk dalam koleksi ini.</p>
            <p class="mt-1.5 text-sm text-muted-foreground">Florist kami sedang menyiapkan rangkaian bunga baru.</p>
            <Link href="/" class="mt-6 inline-flex min-h-11 items-center rounded-xl bg-primary px-5 text-sm font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                Lihat semua koleksi bunga
            </Link>
        </div>
    </section>
</template>

