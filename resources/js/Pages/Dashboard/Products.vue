<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { ProductsProps } from '@/types';
import { computed, ref } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<ProductsProps>();

const query = ref('');
const category = ref('All');

const stockStatus = (stock: number): string =>
    stock === 0 ? 'Out of stock' : stock < 10 ? 'Low stock' : 'In stock';

const filtered = computed(() =>
    props.products.filter(
        (p) =>
            (category.value === 'All' || p.category === category.value) &&
            p.name.toLowerCase().includes(query.value.toLowerCase()),
    ),
);
</script>

<template>
    <PageHeader title="Products" subtitle="Catalog with live stock status.">
        <template #actions>
            <span class="rounded-full bg-muted px-2.5 py-1 text-xs font-semibold text-muted-foreground">
                {{ filtered.length }} of {{ products.length }}
            </span>
        </template>
    </PageHeader>

    <CardSection title="Catalog" subtitle="Search and filter update the table below.">
        <template #actions>
            <fieldset class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                <legend class="sr-only">Catalog filters</legend>
                <label for="product-search" class="sr-only">Search products</label>
                <input
                    id="product-search"
                    v-model="query"
                    type="search"
                    placeholder="Search products"
                    class="min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors placeholder:text-muted-foreground focus:border-ring focus:ring-2 focus:ring-ring/20 sm:w-56"
                />
                <label for="product-category" class="sr-only">Filter by category</label>
                <select
                    id="product-category"
                    v-model="category"
                    class="min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20 sm:w-auto"
                >
                    <option value="All">All categories</option>
                    <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                </select>
            </fieldset>
        </template>
        <div class="-m-5 sm:-m-6">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <th class="px-3 py-3 font-semibold text-[11px] sm:px-6 sm:text-xs">Product</th>
                        <th class="hidden px-5 py-3 font-semibold md:table-cell">Category</th>
                        <th class="hidden px-5 py-3 text-right font-semibold sm:table-cell">Price</th>
                        <th class="hidden px-5 py-3 text-right font-semibold lg:table-cell">Stock</th>
                        <th class="px-3 py-3 text-right font-semibold text-[11px] sm:px-6 sm:text-xs">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in filtered"
                        :key="product.id"
                        class="border-b last:border-0 hover:bg-muted/60"
                    >
                        <td class="break-words px-3 py-3.5 font-semibold sm:px-6">{{ product.name }}</td>
                        <td class="hidden px-5 py-3 text-muted-foreground md:table-cell">{{ product.category }}</td>
                        <td class="hidden px-5 py-3 text-right font-medium sm:table-cell">{{ product.price }}</td>
                        <td class="hidden px-5 py-3 text-right lg:table-cell">{{ product.stock }}</td>
                        <td class="px-3 py-3 text-right sm:px-6">
                            <StatusBadge :status="stockStatus(product.stock)" />
                        </td>
                    </tr>
                    <tr v-if="filtered.length === 0">
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-muted-foreground">
                            No products match your filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </CardSection>
</template>
