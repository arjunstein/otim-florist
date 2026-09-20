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
    <article class="group relative flex flex-col overflow-hidden rounded-[1.75rem] border border-border/80 bg-card shadow-xs transition-all duration-300 hover:-translate-y-1.5 hover:border-primary/35 hover:shadow-xl motion-reduce:transform-none motion-reduce:transition-none">
        <Link :href="`/products/${props.product.slug}`" class="flex h-full flex-col focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            <div class="relative grid aspect-[4/3] w-full place-items-center overflow-hidden bg-secondary/60">
                <img
                    v-if="props.product.imageUrl"
                    :src="props.product.imageUrl"
                    :alt="props.product.name"
                    class="size-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 motion-reduce:transition-none"
                    loading="lazy"
                />
                <div v-else class="grid size-full place-items-center bg-gradient-to-br from-secondary/40 via-secondary to-primary/5">
                    <svg class="size-24 text-primary/40 transition-transform duration-500 group-hover:scale-110 motion-reduce:transition-none" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".2" />
                        <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                        <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                    </svg>
                </div>

                <span
                    v-if="props.product.salePrice"
                    class="absolute left-3.5 top-3.5 inline-flex items-center gap-1 rounded-full bg-accent px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-accent-foreground shadow-sm"
                >
                    Promo
                </span>
            </div>

            <div class="flex flex-1 flex-col p-5 sm:p-6">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex rounded-full bg-secondary/80 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-secondary-foreground">
                        {{ props.product.category.name }}
                    </span>
                </div>

                <h2 class="mt-2.5 text-xl leading-snug font-semibold text-foreground transition-colors duration-200 group-hover:text-primary">
                    {{ props.product.name }}
                </h2>

                <div class="mt-auto pt-4 flex items-center justify-between gap-2 border-t border-border/50">
                    <div class="flex flex-col">
                        <span v-if="props.product.salePrice" class="text-xs text-muted-foreground line-through">
                            {{ priceFormatter.format(props.product.price) }}
                        </span>
                        <span class="text-base sm:text-lg font-bold text-primary">
                            {{ priceFormatter.format(props.product.salePrice ?? props.product.price) }}
                        </span>
                    </div>

                    <div class="grid size-9 place-items-center rounded-full bg-secondary text-primary transition-all duration-300 group-hover:bg-primary group-hover:text-primary-foreground group-hover:shadow-xs">
                        <svg class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </Link>
    </article>
</template>

