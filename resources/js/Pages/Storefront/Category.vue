<script setup lang="ts">
import ProductCard from '@/Components/Storefront/ProductCard.vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

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
</script>

<template>
    <Head :title="`Koleksi ${category.name} | Otim Florist`">
        <meta name="description" :content="`Jelajahi koleksi ${category.name.toLowerCase()} dari Otim Florist.`" />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" :content="`Koleksi ${category.name} | Otim Florist`" />
        <meta property="og:description" :content="`Jelajahi koleksi ${category.name.toLowerCase()} dari Otim Florist.`" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonicalUrl" />
    </Head>

    <section class="border-b bg-secondary/45">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <nav aria-label="Jejak navigasi">
                <ol class="flex min-h-11 items-center gap-2 text-sm font-semibold text-muted-foreground">
                    <li><Link href="/" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Beranda</Link></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-foreground">{{ category.name }}</li>
                </ol>
            </nav>
            <p class="mt-8 text-sm font-semibold uppercase tracking-[0.16em] text-primary">Koleksi</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">{{ category.name }}</h1>
            <p class="mt-4 text-base text-muted-foreground">
                {{ category.productCount }} produk tersedia.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div v-if="products.length" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <ProductCard v-for="product in products" :key="product.slug" :product="product" />
        </div>
        <div v-else class="rounded-2xl border border-dashed bg-card p-8 text-center">
            <p class="font-semibold">Belum ada produk dalam kategori ini.</p>
            <Link href="/" class="mt-4 inline-flex min-h-11 items-center rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">
                Lihat semua bunga
            </Link>
        </div>
    </section>
</template>
