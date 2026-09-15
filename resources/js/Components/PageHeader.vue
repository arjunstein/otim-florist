<script setup lang="ts">
defineProps<{
    title: string;
    subtitle: string;
}>();
</script>

<template>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">Store operations</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight md:text-3xl">{{ title }}</h1>
            <p class="mt-1 text-sm leading-6 text-muted-foreground">{{ subtitle }}</p>
        </div>
        <div class="flex w-full items-center gap-2 sm:w-auto">
            <slot name="actions" />
        </div>
    </div>
</template>
