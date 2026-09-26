<script setup lang="ts">
defineProps<{
    label: string;
    value: string;
    delta: string;
    up: boolean;
}>();
</script>

<template>
    <article class="rounded-2xl border border-border/80 bg-card/85 p-5 shadow-2xs backdrop-blur-xs transition-all duration-200 hover:border-primary/40 hover:shadow-md sm:p-6">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-muted-foreground">{{ label }}</p>
            <span :class="['size-2.5 rounded-full ring-4', up ? 'bg-primary ring-primary/20' : 'bg-warning ring-warning/20']" aria-hidden="true" />
        </div>
        <p class="mt-3 text-3xl font-semibold tracking-tight text-foreground">{{ value }}</p>
        <p :class="['mt-2 flex items-center gap-1.5 text-sm font-medium', up ? 'text-primary' : 'text-muted-foreground']">
            <svg v-if="up" class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M3 10 6.5 6.5 9 9l4-4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.5 5h3.5v3.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span v-else class="size-1.5 shrink-0 rounded-full bg-muted-foreground" aria-hidden="true" />
            <span>{{ delta }}</span>
        </p>
    </article>
</template>
