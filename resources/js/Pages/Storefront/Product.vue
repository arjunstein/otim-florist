<script setup lang="ts">
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: StorefrontLayout });

const props = defineProps<{
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
</script>

<template>
    <Head :title="product.name">
        <meta name="description" :content="`${product.name} from Otim Florist.`" />
    </Head>

    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 sm:py-14 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-20">
        <div>
            <nav aria-label="Breadcrumb">
                <ol class="flex min-h-11 flex-wrap items-center gap-2 text-sm font-semibold text-muted-foreground">
                    <li><Link href="/" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Home</Link></li>
                    <li aria-hidden="true">/</li>
                    <li><Link :href="`/categories/${product.category.slug}`" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">{{ product.category.name }}</Link></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-foreground">{{ product.name }}</li>
                </ol>
            </nav>
            <div class="mt-6 grid aspect-square max-w-xl place-items-center rounded-[2rem] border bg-secondary/70 shadow-sm">
                <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" class="size-full rounded-[2rem] object-cover" />
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
            <p class="mt-7 text-base leading-7 text-muted-foreground">{{ product.description || 'A carefully selected arrangement, prepared with the same attention to every meaningful occasion.' }}</p>
            <Link href="/" class="mt-8 inline-flex min-h-11 items-center rounded-xl bg-primary px-5 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90">
                Explore more flowers
            </Link>
        </div>
    </section>
</template>
