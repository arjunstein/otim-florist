<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import StatCard from '@/Components/StatCard.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { DashboardProps } from '@/types';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<DashboardProps>();

const maxSale = computed(() => Math.max(...props.sales.map((s) => s.value), 1));
</script>

<template>
    <PageHeader title="Overview" subtitle="Today's store performance at a glance.">
        <template #actions>
            <Link
                href="/products"
                class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm font-medium hover:bg-stone-50"
            >
                View products
            </Link>
        </template>
    </PageHeader>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard
            v-for="stat in stats"
            :key="stat.label"
            :label="stat.label"
            :value="stat.value"
            :delta="stat.delta"
            :up="stat.up"
            :icon="stat.icon"
        />
    </section>

    <section class="grid gap-4 xl:grid-cols-5">
        <CardSection title="Weekly sales" subtitle="Bouquets sold per day." class="xl:col-span-3">
            <div class="flex h-44 items-end gap-2">
                <div
                    v-for="point in sales"
                    :key="point.label"
                    class="flex h-full flex-1 flex-col items-center justify-end gap-1.5"
                >
                    <span class="text-[11px] font-medium text-stone-500">{{ point.value }}</span>
                    <div
                        class="w-full rounded-t-md bg-emerald-500"
                        :style="{ height: `${(point.value / maxSale) * 70}%` }"
                    />
                    <span class="text-[11px] text-stone-400">{{ point.label }}</span>
                </div>
            </div>
        </CardSection>

        <CardSection title="Low stock" subtitle="Restock these supplies soon." class="xl:col-span-2">
            <ul class="space-y-2">
                <li
                    v-for="item in lowStock"
                    :key="item.name"
                    class="flex items-center justify-between rounded-lg bg-stone-50 px-3 py-2.5 text-sm"
                >
                    <span class="font-medium">{{ item.name }}</span>
                    <span class="text-xs font-semibold text-red-600">{{ item.left }} left</span>
                </li>
            </ul>
        </CardSection>
    </section>

    <CardSection title="Recent orders" subtitle="Latest 5 customer orders.">
        <template #actions>
            <span class="text-xs text-stone-400">{{ orders.length }} orders</span>
        </template>
        <div class="-m-5 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="border-b border-stone-100 text-xs uppercase tracking-wide text-stone-400">
                        <th class="px-5 py-3 font-medium">Order</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Item</th>
                        <th class="px-5 py-3 text-right font-medium">Total</th>
                        <th class="px-5 py-3 text-right font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in orders"
                        :key="order.id"
                        class="border-b border-stone-50 last:border-0 hover:bg-stone-50/60"
                    >
                        <td class="px-5 py-3 font-semibold">{{ order.id }}</td>
                        <td class="px-5 py-3">{{ order.customer }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ order.item }}</td>
                        <td class="px-5 py-3 text-right font-medium">{{ order.total }}</td>
                        <td class="px-5 py-3 text-right">
                            <StatusBadge :status="order.status" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </CardSection>
</template>
