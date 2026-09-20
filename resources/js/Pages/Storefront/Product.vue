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

    <section class="mx-auto grid max-w-7xl gap-12 px-4 py-10 sm:px-6 sm:py-16 lg:grid-cols-2 lg:items-start lg:gap-16 lg:px-8 lg:py-20">
        <div>
            <nav aria-label="Jejak navigasi">
                <ol class="flex min-h-11 flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                    <li><Link href="/" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Beranda</Link></li>
                    <li aria-hidden="true" class="text-border">/</li>
                    <li><Link :href="`/categories/${product.category.slug}`" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">{{ product.category.name }}</Link></li>
                    <li aria-hidden="true" class="text-border">/</li>
                    <li aria-current="page" class="text-foreground truncate max-w-[200px] sm:max-w-xs">{{ product.name }}</li>
                </ol>
            </nav>

            <div class="relative mt-4 grid aspect-[4/3] w-full max-w-xl place-items-center overflow-hidden rounded-[2.25rem] border border-border/80 bg-secondary/50 shadow-sm">
                <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" class="size-full object-cover" />
                <div v-else class="grid size-full place-items-center bg-gradient-to-br from-secondary/40 via-secondary to-primary/5">
                    <svg class="size-48 text-primary/30" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".2" />
                        <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                        <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                    </svg>
                </div>

                <span
                    v-if="product.salePrice"
                    class="absolute left-4 top-4 rounded-full bg-accent px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider text-accent-foreground shadow-sm"
                >
                    Promo
                </span>
            </div>
        </div>

        <div class="flex flex-col max-w-xl">
            <div class="flex items-center gap-2">
                <span class="inline-flex rounded-full bg-secondary px-3 py-1 text-xs font-semibold uppercase tracking-wider text-secondary-foreground">
                    {{ product.category.name }}
                </span>
                <span class="text-xs text-muted-foreground">• Rangkaian Segar</span>
            </div>

            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-foreground sm:text-4xl lg:text-5xl">
                {{ product.name }}
            </h1>

            <div class="mt-6 flex flex-wrap items-baseline gap-x-3 gap-y-1">
                <span class="text-3xl font-bold text-primary">
                    {{ priceFormatter.format(product.salePrice ?? product.price) }}
                </span>
                <span v-if="product.salePrice" class="text-base text-muted-foreground line-through">
                    {{ priceFormatter.format(product.price) }}
                </span>
                <span v-if="product.salePrice" class="rounded-full bg-accent/15 px-2.5 py-0.5 text-xs font-bold text-accent">
                    Hemat {{ Math.round((1 - product.salePrice / product.price) * 100) }}%
                </span>
            </div>

            <div class="mt-8 border-t border-border/70 pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Deskripsi Rangkaian</h2>
                <p class="mt-3 text-base leading-relaxed text-foreground/80">
                    {{ product.description || 'Rangkaian bunga pilihan yang disiapkan dengan ketelitian dan penuh perhatian oleh florist kami untuk menyempurnakan setiap momen berharga Anda.' }}
                </p>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                <a
                    v-if="whatsappUrl"
                    :href="whatsappUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex min-h-12 flex-1 items-center justify-center gap-2.5 rounded-xl bg-primary px-6 text-sm font-semibold text-primary-foreground shadow-md transition-all duration-200 hover:bg-primary/95 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                >
                    <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <span>Pesan via WhatsApp</span>
                </a>
                <Link
                    href="/"
                    class="inline-flex min-h-12 items-center justify-center rounded-xl border border-border bg-card px-5 text-sm font-semibold text-foreground shadow-xs transition-colors duration-200 hover:bg-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                >
                    Lihat Koleksi Lain
                </Link>
            </div>

            <div class="mt-10 grid gap-3 rounded-2xl border border-border/80 bg-secondary/40 p-5 text-xs text-foreground/80">
                <div class="flex items-center gap-3">
                    <svg class="size-4 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <span><strong>Garansi Bunga Segar:</strong> Dipilih langsung dari kuncup terbaik di hari pengiriman.</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="size-4 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <span><strong>Gratis Kartu Ucapan:</strong> Sertakan pesan manis Anda saat konfirmasi pesanan via WhatsApp.</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="size-4 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="1" y="3" width="15" height="13" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                    <span><strong>Pengiriman Aman:</strong> Kurir terlatih menjaga posisi bunga tetap aman dan tegak.</span>
                </div>
            </div>
        </div>
    </section>
</template>

