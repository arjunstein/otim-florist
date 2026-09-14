<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    status: string;
}>();

const classes = computed(
    () =>
        (
            {
                Pending: 'bg-amber-100 text-amber-800 ring-amber-200',
                Arranging: 'bg-sky-100 text-sky-800 ring-sky-200',
                Delivered: 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                'In stock': 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                'Low stock': 'bg-amber-100 text-amber-800 ring-amber-200',
                'Out of stock': 'bg-red-100 text-red-700 ring-red-200',
            } as Record<string, string>
        )[props.status] ?? 'bg-stone-100 text-stone-600 ring-stone-200',
);
</script>

<template>
    <span
        :class="[
            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset',
            classes,
        ]"
    >
        {{ status }}
    </span>
</template>
