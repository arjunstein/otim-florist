<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    product: {
        name: string;
        slug: string;
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
    <article class="group overflow-hidden rounded-[1.5rem] border bg-card/90 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl motion-reduce:transform-none motion-reduce:transition-none">
        <Link :href="`/products/${props.product.slug}`" class="block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            <div class="relative grid aspect-[4/3] place-items-center overflow-hidden bg-secondary/80">
                <img v-if="props.product.imageUrl" :src="props.product.imageUrl" :alt="props.product.name" class="size-full object-cover transition-transform duration-500 group-hover:scale-105 motion-reduce:transition-none" loading="lazy" />
                <svg v-else class="size-28 text-primary/75 transition-transform duration-500 group-hover:scale-110 motion-reduce:transition-none" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".18" />
                    <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                    <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                </svg>
            </div>
            <div class="p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-muted-foreground">{{ props.product.category.name }}</p>
                <h2 class="mt-2 text-2xl leading-tight">{{ props.product.name }}</h2>
                <div class="mt-4 flex flex-wrap items-baseline gap-x-2 gap-y-1">
                    <p v-if="props.product.salePrice" class="text-sm text-muted-foreground line-through">{{ priceFormatter.format(props.product.price) }}</p>
                    <p class="font-semibold text-primary">{{ priceFormatter.format(props.product.salePrice ?? props.product.price) }}</p>
                </div>
            </div>
        </Link>
    </article>
</template>
