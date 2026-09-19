<script setup lang="ts">
import ProductCard from '@/Components/Storefront/ProductCard.vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: StorefrontLayout });

defineProps<{
    category: {
        name: string;
        productCount: number;
    };
    products: Array<{
        name: string;
        slug: string;
        price: number;
        category: {
            name: string;
            slug: string;
        };
    }>;
}>();
</script>

<template>
    <Head :title="`${category.name} flowers`">
        <meta name="description" :content="`Browse ${category.name.toLowerCase()} flowers from Otim Florist.`" />
    </Head>

    <section class="border-b bg-secondary/45">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <Link href="/" class="inline-flex min-h-11 items-center gap-2 rounded-xl text-sm font-semibold text-muted-foreground transition-colors hover:text-foreground">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                All flowers
            </Link>
            <p class="mt-8 text-sm font-semibold uppercase tracking-[0.16em] text-primary">Collection</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">{{ category.name }}</h1>
            <p class="mt-4 text-base text-muted-foreground">
                {{ category.productCount }} {{ category.productCount === 1 ? 'arrangement' : 'arrangements' }} available.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div v-if="products.length" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <ProductCard v-for="product in products" :key="product.slug" :product="product" />
        </div>
        <div v-else class="rounded-2xl border border-dashed bg-card p-8 text-center">
            <p class="font-semibold">No arrangements in this category yet.</p>
            <Link href="/" class="mt-4 inline-flex min-h-11 items-center rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">
                Browse all flowers
            </Link>
        </div>
    </section>
</template>
