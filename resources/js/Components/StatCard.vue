<script setup lang="ts">
defineProps<{
    label: string;
    value: string;
    delta: string;
    up: boolean;
}>();
</script>

<template>
    <article class="rounded-2xl border bg-card p-5 shadow-sm transition-shadow duration-200 hover:shadow-md sm:p-6">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-muted-foreground">{{ label }}</p>
            <span :class="['size-2 rounded-full', up ? 'bg-primary' : 'bg-warning']" aria-hidden="true" />
        </div>
        <p class="mt-3 text-3xl font-semibold tracking-tight">{{ value }}</p>
        <p :class="['mt-2 flex items-center gap-1.5 text-sm font-medium', up ? 'text-success-foreground' : 'text-muted-foreground']">
            <svg v-if="up" class="size-4" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M3 10 6.5 6.5 9 9l4-4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.5 5h3.5v3.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span v-else class="size-1.5 rounded-full bg-muted-foreground" aria-hidden="true" />
            <span>{{ delta }}</span>
        </p>
    </article>
</template>
