<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import StatCard from '@/Components/StatCard.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { DashboardProps } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: DashboardLayout });

defineProps<DashboardProps>();

const priceFormatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
});
</script>

<template>
    <Head title="Dashboard" />

    <PageHeader title="Dashboard" subtitle="Your catalog at a glance." />

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard v-for="stat in stats" :key="stat.label" :label="stat.label" :value="stat.value" :delta="stat.delta" :up="stat.up" />
    </section>

    <section class="grid gap-4 xl:grid-cols-5">
        <CardSection title="Recently added" subtitle="Latest products in your catalog." class="xl:col-span-3">
            <template #actions>
                <Link href="/products" class="inline-flex min-h-11 items-center rounded-xl px-3 text-sm font-semibold text-primary transition-colors hover:bg-secondary">
                    View all
                </Link>
            </template>
            <ul v-if="recentProducts.length" class="-mx-5 divide-y sm:-mx-6">
                <li v-for="product in recentProducts" :key="product.id">
                    <Link :href="`/products?search=${encodeURIComponent(product.name)}`" class="flex min-h-16 items-center gap-3 px-5 py-3 transition-colors hover:bg-muted/60 sm:px-6">
                        <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" class="size-11 shrink-0 rounded-lg object-cover" />
                        <div v-else class="grid size-11 shrink-0 place-items-center rounded-lg bg-secondary text-primary" aria-hidden="true">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z" />
                                <path d="M12 8v8M8 12h8" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold">{{ product.name }}</p>
                            <p class="mt-0.5 text-xs text-muted-foreground">{{ product.categoryName }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p v-if="product.salePrice" class="text-xs text-muted-foreground line-through">{{ priceFormatter.format(product.price) }}</p>
                            <p :class="['text-sm font-semibold', product.salePrice ? 'text-accent' : 'text-foreground']">{{ priceFormatter.format(product.salePrice ?? product.price) }}</p>
                        </div>
                    </Link>
                </li>
            </ul>
            <div v-else class="rounded-xl border border-dashed bg-muted/30 p-8 text-center">
                <p class="font-semibold">No products yet</p>
                <p class="mt-1 text-sm text-muted-foreground">Add your first product to start building catalog.</p>
                <Link href="/products" class="mt-4 inline-flex min-h-11 items-center rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">Add product</Link>
            </div>
        </CardSection>

        <CardSection title="Category overview" subtitle="Products in each category." class="xl:col-span-2">
            <template #actions>
                <Link href="/categories" class="inline-flex min-h-11 items-center rounded-xl px-3 text-sm font-semibold text-primary transition-colors hover:bg-secondary">
                    View all
                </Link>
            </template>
            <ul v-if="categorySummary.length" class="flex flex-col gap-2">
                <li v-for="category in categorySummary" :key="category.id">
                    <Link href="/categories" class="flex min-h-12 items-center justify-between rounded-xl bg-muted/60 px-3 text-sm transition-colors hover:bg-secondary">
                        <span class="font-medium">{{ category.name }}</span>
                        <span class="rounded-full bg-background px-2.5 py-1 text-xs font-semibold text-muted-foreground">{{ category.productCount }} {{ category.productCount === 1 ? 'product' : 'products' }}</span>
                    </Link>
                </li>
            </ul>
            <div v-else class="rounded-xl border border-dashed bg-muted/30 p-8 text-center">
                <p class="font-semibold">No categories yet</p>
                <Link href="/categories" class="mt-4 inline-flex min-h-11 items-center rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">Add category</Link>
            </div>
        </CardSection>
    </section>

    <CardSection title="Most viewed products" subtitle="Ranked by public product-page visits.">
        <template #actions>
            <span v-if="mostClickedProducts.length" class="rounded-full bg-muted px-2.5 py-1 text-xs font-semibold text-muted-foreground">Top {{ mostClickedProducts.length }}</span>
        </template>
        <ol v-if="mostClickedProducts.length" class="-mx-5 divide-y sm:-mx-6">
            <li v-for="(product, index) in mostClickedProducts" :key="product.id">
                <Link :href="`/products?search=${encodeURIComponent(product.name)}`" class="grid min-h-16 grid-cols-[2.5rem_2.75rem_minmax(0,1fr)_auto] items-center gap-3 px-5 py-3 transition-colors hover:bg-muted/60 sm:px-6">
                    <span :class="['grid size-9 place-items-center rounded-full text-sm font-semibold', index === 0 ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground']">{{ index + 1 }}</span>
                    <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" class="size-11 rounded-lg object-cover" loading="lazy" />
                    <span v-else class="grid size-11 place-items-center rounded-lg bg-secondary text-sm font-semibold text-primary" aria-hidden="true">{{ product.name.charAt(0) }}</span>
                    <span class="min-w-0">
                        <span class="block truncate text-base font-semibold">{{ product.name }}</span>
                        <span class="block truncate text-sm text-muted-foreground">{{ product.categoryName }}</span>
                    </span>
                    <span class="text-right">
                        <span class="block text-base font-semibold text-primary">{{ product.clickCount }}</span>
                        <span class="block text-xs font-medium text-muted-foreground">views</span>
                    </span>
                </Link>
            </li>
        </ol>
        <div v-else class="rounded-xl border border-dashed bg-muted/30 p-8 text-center">
            <p class="font-semibold">No product clicks yet</p>
            <p class="mt-1 text-sm text-muted-foreground">Visits to public product pages will appear here.</p>
        </div>
    </CardSection>
</template>
