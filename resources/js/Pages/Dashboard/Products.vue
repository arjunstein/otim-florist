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
            <span class="text-xs text-stone-400">{{ filtered.length }} of {{ products.length }}</span>
        </template>
    </PageHeader>

    <CardSection title="Catalog" subtitle="Search and filter update the table below.">
        <template #actions>
            <div class="flex flex-col gap-2 sm:flex-row">
                <input
                    v-model="query"
                    type="search"
                    placeholder="Search products…"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 sm:w-52"
                />
                <select
                    v-model="category"
                    class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600"
                >
                    <option value="All">All categories</option>
                    <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                </select>
            </div>
        </template>
        <div class="-m-5 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="border-b border-stone-100 text-xs uppercase tracking-wide text-stone-400">
                        <th class="px-5 py-3 font-medium">Product</th>
                        <th class="px-5 py-3 font-medium">Category</th>
                        <th class="px-5 py-3 text-right font-medium">Price</th>
                        <th class="px-5 py-3 text-right font-medium">Stock</th>
                        <th class="px-5 py-3 text-right font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in filtered"
                        :key="product.id"
                        class="border-b border-stone-50 last:border-0 hover:bg-stone-50/60"
                    >
                        <td class="px-5 py-3 font-semibold">{{ product.name }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ product.category }}</td>
                        <td class="px-5 py-3 text-right font-medium">{{ product.price }}</td>
                        <td class="px-5 py-3 text-right">{{ product.stock }}</td>
                        <td class="px-5 py-3 text-right">
                            <StatusBadge :status="stockStatus(product.stock)" />
                        </td>
                    </tr>
                    <tr v-if="filtered.length === 0">
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-stone-400">
                            No products match your filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </CardSection>
</template>
