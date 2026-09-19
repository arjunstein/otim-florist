<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import StatCard from '@/Components/StatCard.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { DashboardProps } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<DashboardProps>();

const maxSale = computed(() => Math.max(...props.sales.map((s) => s.value), 1));
</script>

<template>
    <Head title="Dashboard" />

    <PageHeader title="Dashboard" subtitle="Today's store performance at a glance.">
        <template #actions>
            <Link
                href="/products"
                class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 sm:w-auto"
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
        />
    </section>

    <section class="grid gap-4 xl:grid-cols-5">
        <CardSection title="Weekly sales" subtitle="Bouquets sold per day." class="xl:col-span-3">
            <div class="flex h-48 items-end gap-2" aria-label="Weekly sales chart">
                <div
                    v-for="point in sales"
                    :key="point.label"
                    class="flex h-full flex-1 flex-col items-center justify-end gap-1.5"
                >
                    <span class="text-xs font-medium text-muted-foreground">{{ point.value }}</span>
                    <div
                        class="w-full rounded-t-lg bg-primary transition-colors duration-200 hover:bg-primary/80"
                        :style="{ height: `${(point.value / maxSale) * 70}%` }"
                        :aria-label="`${point.label}: ${point.value} bouquets sold`"
                    />
                    <span class="text-xs text-muted-foreground">{{ point.label }}</span>
                </div>
            </div>
        </CardSection>

        <CardSection title="Low stock" subtitle="Restock these supplies soon." class="xl:col-span-2">
            <ul class="flex flex-col gap-2">
                <li
                    v-for="item in lowStock"
                    :key="item.name"
                    class="flex min-h-12 items-center justify-between rounded-xl bg-muted px-3 text-sm"
                >
                    <span class="font-medium">{{ item.name }}</span>
                    <span class="text-xs font-semibold text-destructive-foreground">{{ item.left }} left</span>
                </li>
            </ul>
        </CardSection>
    </section>

    <CardSection title="Recent orders" subtitle="Latest 5 customer orders.">
        <template #actions>
            <span class="rounded-full bg-muted px-2.5 py-1 text-xs font-semibold text-muted-foreground">{{ orders.length }} orders</span>
        </template>
        <div class="-m-5 sm:-m-6">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <th class="px-3 py-3 font-semibold text-[11px] sm:px-6 sm:text-xs">Order</th>
                        <th class="px-2 py-3 font-semibold text-[11px] sm:px-5 sm:text-xs">Customer</th>
                        <th class="hidden px-5 py-3 font-semibold md:table-cell">Item</th>
                        <th class="hidden px-5 py-3 text-right font-semibold sm:table-cell">Total</th>
                        <th class="px-3 py-3 text-right font-semibold text-[11px] sm:px-6 sm:text-xs">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in orders"
                        :key="order.id"
                        class="border-b last:border-0 hover:bg-muted/60"
                    >
                        <td class="px-3 py-3.5 font-semibold sm:px-6">{{ order.id }}</td>
                        <td class="break-words px-2 py-3 sm:px-5">{{ order.customer }}</td>
                        <td class="hidden px-5 py-3 text-muted-foreground md:table-cell">{{ order.item }}</td>
                        <td class="hidden px-5 py-3 text-right font-medium sm:table-cell">{{ order.total }}</td>
                        <td class="px-3 py-3 text-right sm:px-6">
                            <StatusBadge :status="order.status" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </CardSection>
</template>
