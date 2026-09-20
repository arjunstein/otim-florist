<script setup lang="ts">
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import SeoJsonLd from '@/Components/SeoJsonLd.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: StorefrontLayout });

const props = defineProps<{
    canonicalUrl: string;
    whatsappUrl: string | null;
    product: {
        name: string;
        description: string | null;
        imageUrl: string | null;
        price: number;
        salePrice: number | null;
        category: {
            name: string;
            slug: string;
        };
    };
}>();

const priceFormatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
});

const pageTitle = `${props.product.name} | ${props.product.category.name} | Otim Florist`;
const pageDescription = (props.product.description || `${props.product.name} dari Otim Florist.`).slice(0, 160);
const productSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: props.product.name,
    description: pageDescription,
    url: props.canonicalUrl,
    image: props.product.imageUrl ? [props.product.imageUrl] : undefined,
    brand: {
        '@type': 'Brand',
        name: 'Otim Florist',
    },
}).replace(/</g, '\\u003c');
</script>

<template>
    <Head :title="pageTitle">
        <meta name="description" :content="pageDescription" />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="pageDescription" />
        <meta property="og:type" content="product" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta v-if="product.imageUrl" property="og:image" :content="product.imageUrl" />
    </Head>

    <SeoJsonLd :content="productSchema" />

    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 sm:py-14 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-20">
        <div>
            <nav aria-label="Jejak navigasi">
                <ol class="flex min-h-11 flex-wrap items-center gap-2 text-sm font-semibold text-muted-foreground">
                    <li><Link href="/" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Beranda</Link></li>
                    <li aria-hidden="true">/</li>
                    <li><Link :href="`/categories/${product.category.slug}`" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">{{ product.category.name }}</Link></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-foreground">{{ product.name }}</li>
                </ol>
            </nav>
                <div class="mt-6 grid aspect-[4/3] max-w-xl place-items-center overflow-hidden rounded-[2rem] border bg-secondary/70 shadow-sm">
                    <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" class="size-full object-cover" />
                <svg v-else class="size-52 text-primary" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".18" />
                    <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                    <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                </svg>
            </div>
        </div>
        <div class="max-w-xl">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-primary">{{ product.category.name }}</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">{{ product.name }}</h1>
            <div class="mt-5 flex flex-wrap items-baseline gap-x-3 gap-y-1">
                <p v-if="product.salePrice" class="text-base text-muted-foreground line-through">{{ priceFormatter.format(product.price) }}</p>
                <p class="text-2xl font-semibold text-primary">{{ priceFormatter.format(product.salePrice ?? product.price) }}</p>
            </div>
            <p class="mt-7 text-base leading-7 text-muted-foreground">{{ product.description || 'Rangkaian pilihan yang disiapkan dengan perhatian untuk setiap momen bermakna.' }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a
                    v-if="whatsappUrl"
                    :href="whatsappUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-primary px-5 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M20.5 11.7a8.4 8.4 0 0 1-12.4 7.4L4 20l.9-4a8.5 8.5 0 1 1 15.6-4.3Z" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8.7 8.4c.2-.5.4-.5.7-.5h.5c.2 0 .4.1.5.4l.7 1.7c.1.2.1.4 0 .6l-.5.7c.7 1.3 1.8 2.4 3.1 3.1l.7-.5c.2-.1.4-.1.6 0l1.7.7c.3.1.4.3.4.5v.5c0 .3 0 .5-.5.7-.6.2-1.3.3-2 .1-3.6-1-6.4-3.8-7.4-7.4-.2-.7-.1-1.4.1-2Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Beli via WhatsApp
                </a>
                <Link href="/" class="inline-flex min-h-11 items-center rounded-xl border bg-background px-5 text-sm font-semibold shadow-sm transition-colors duration-200 hover:bg-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                    Lihat koleksi lainnya
                </Link>
            </div>
        </div>
    </section>
</template>
