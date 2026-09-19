<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    status: string;
}>();

const classes = computed(
    () =>
        (
            {
                Pending: 'bg-warning text-warning-foreground',
                Arranging: 'bg-info text-info-foreground',
                Delivered: 'bg-success text-success-foreground',
                'In stock': 'bg-success text-success-foreground',
                'Low stock': 'bg-warning text-warning-foreground',
                'Out of stock': 'bg-destructive text-destructive-foreground',
            } as Record<string, string>
        )[props.status] ?? 'bg-muted text-muted-foreground',
);
</script>

<template>
    <span
        :class="[
            'inline-flex min-h-6 items-center rounded-full px-2.5 text-xs font-semibold',
            classes,
        ]"
    >
        {{ status }}
    </span>
</template>
